<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class PayPal
{
    /**
     * @param string $serverUrl
     * @param Plan $plan
     * @return string
     * @throws GuzzleException|Exception
     */
    public static function createPlan(string $serverUrl, Plan $plan): string
    {
        try {
            if (empty(Setting::get("paypal_product_id"))) {
                self::createProduct($serverUrl);
            }
            $client = new GuzzleHttp\Client();
            $response = $client->post(self::getPaypalURL('/v1/billing/plans'), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "json" => [
                        "product_id" => Setting::get("paypal_product_id"),
                        "name" => $plan->name,
                        "status" => $plan->getEnabled() ? "ACTIVE" : "INACTIVE",
                        "billing_cycles" => [
                            [
                                "frequency" => [
                                    "interval_unit" => $plan->getFrequencyUnit(),
                                    "interval_count" => $plan->frequency
                                ],
                                "tenure_type" => "REGULAR",
                                "sequence" => 1,
                                "total_cycles" => $plan->getTotalCycles(),
                                "pricing_scheme" => [
                                    "fixed_price" => [
                                        "value" => $plan->getPrice(),
                                        "currency_code" => $plan->getCurrency()
                                    ]
                                ]
                            ]
                        ],
                        "payment_preferences" => [
                            "auto_bill_outstanding" => true,
                            "payment_failure_threshold" => 0
                        ]
                    ]
                ]
            );
            $body = json_decode($response->getBody()->getContents());
            return $body->id;
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param bool $generate
     * @return string
     * @throws Exception|GuzzleException
     */
    public static function getAccessToken(bool $generate = true): string
    {
        if ($generate || empty(Setting::get("paypal_access_token")) || Setting::get("paypal_access_token") <= time()) {
            $client = new GuzzleHttp\Client();
            $response = $client->post(self::getPaypalURL('/v1/oauth2/token'), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Accept" => "application/json",
                        "Accept-Language" => "en_US"
                    ],
                    "form_params" => [
                        "grant_type" => "client_credentials"
                    ]
                ]
            );
            $body = json_decode($response->getBody()->getContents());
            Setting::apply([
                "paypal_access_token" => $body->access_token,
                "paypal_token_expires_in" => time() + $body->expires_in
            ]);
            return $body->access_token;
        } else {
            return Setting::get("paypal_access_token");
        }
    }

    /**
     * @param string $url
     * @return string
     * @throws Exception
     */
    private static function getPaypalURL(string $url): string
    {
        $paypalLink = Setting::get("paypal_sandbox") ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        return "{$paypalLink}{$url}";
    }

    /**
     * @param string $serverUrl
     * @throws Exception|GuzzleException
     */
    public static function createProduct(string $serverUrl)
    {
        try {
            $client = new GuzzleHttp\Client();
            $response = $client->post(self::getPaypalURL('/v1/catalogs/products'), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    "json" => [
                        "name" => Setting::get("application_title"),
                        "description" => Setting::get("application_description"),
                        "type" => "SERVICE",
                        "category" => "SOFTWARE",
                        "image_url" => file_exists(__DIR__ . "/../" . Setting::get("logo_src")) ? $serverUrl . "/" . rawurlencode(Setting::get("logo_src")) : "{$serverUrl}/logo.png",
                        "home_url" => $serverUrl,
                    ]
                ]
            );
            $body = json_decode($response->getBody()->getContents());
            Setting::apply([
                "paypal_product_id" => $body->id,
            ]);
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param RequestException $e
     * @param bool $debug
     * @return string
     */
    public static function getError(RequestException $e, bool $debug = true): string
    {
        if ($e->getResponse() == null) {
            return $e->getMessage();
        } else {
            if ($debug) {
                return $e->getResponse()->getBody()->getContents();
            } else {
                $response = $e->getResponse()->getBody()->getContents();
                error_log($response);
                $body = json_decode($response);
                if ($body) {
                    if (!empty($body->error_description)) {
                        return $body->error_description;
                    }
                    if (!empty($body->details)) {
                        $message = "";
                        foreach ($body->details as $detail) {
                            if (!empty($detail->description)) {
                                $message .= " {$detail->description}";
                            } else if (!empty($detail->issue)) {
                                $message .= " {$detail->issue}";
                            }
                        }
                        return trim($message);
                    }
                    if (!empty($body->message)) {
                        return $body->message;
                    }
                }
                return $response;
            }
        }
    }

    /**
     * @param string $planID
     * @throws Exception|GuzzleException
     */
    public static function deactivatePlan(string $planID)
    {
        try {
            $client = new GuzzleHttp\Client();
            $client->post(self::getPaypalURL("/v1/billing/plans/{$planID}/deactivate"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param Plan $plan
     * @throws Exception|GuzzleException
     */
    public static function updatePlanPricing(Plan $plan)
    {
        try {
            $client = new GuzzleHttp\Client();
            $client->post(self::getPaypalURL("/v1/billing/plans/{$plan->getPaypalPlanID()}/update-pricing-schemes"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "json" => [
                        "pricing_schemes" => [
                            [
                                "billing_cycle_sequence" => 1,
                                "pricing_scheme" => [
                                    "fixed_price" => [
                                        "value" => $plan->getPrice(),
                                        "currency_code" => $plan->getCurrency()
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param string $planID
     * @throws Exception|GuzzleException
     */
    public static function activatePlan(string $planID)
    {
        try {
            $client = new GuzzleHttp\Client();
            $client->post(self::getPaypalURL("/v1/billing/plans/{$planID}/activate"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public static function getWebHooks(): array
    {
        try {
            $client = new GuzzleHttp\Client();
            $response = $client->get(self::getPaypalURL("/v1/notifications/webhooks"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
            $body = json_decode($response->getBody()->getContents());
            return $body->webhooks;
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param string[] $events
     * @param string $url
     * @return object
     * @throws Exception|GuzzleException
     */
    public static function createWebHook(array $events, string $url): ?object
    {
        try {
            $webHooks = self::getWebHooks();
            $result = null;
            foreach ($webHooks as $webHook) {
                if ($webHook->url === $url) {
                    $result = $webHook;
                    foreach ($webHook->event_types as $eventType) {
                        if (in_array($eventType->name, $events)) {
                            foreach ($events as $key => $event) {
                                if ($event === $eventType->name) {
                                    unset($events[$key]);
                                }
                            }
                        }
                    }
                }
            }
            if (count($events) > 0) {
                $eventTypes = [];
                foreach ($events as $event) {
                    $eventTypes[] = [
                        "name" => $event
                    ];
                }
                $client = new GuzzleHttp\Client();
                $response = $client->post(self::getPaypalURL("/v1/notifications/webhooks"), [
                        "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                        "json" => [
                            "url" => $url,
                            "event_types" => $eventTypes
                        ]
                    ]
                );
                return json_decode($response->getBody()->getContents());
            } else {
                return $result;
            }
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param string $subscriptionID
     * @return object
     * @throws Exception|GuzzleException
     */
    public static function getSubscriptionDetails(string $subscriptionID): object
    {
        try {
            $client = new GuzzleHttp\Client();
            $response = $client->get(self::getPaypalURL("/v1/billing/subscriptions/{$subscriptionID}"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param string $subscriptionID
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @return array Array of transactions
     * @throws Exception|GuzzleException
     */
    public static function getSubscriptionTransactions(string $subscriptionID, DateTime $startTime, DateTime $endTime): array
    {
        try {
            $client = new GuzzleHttp\Client();;
            $startTime = $startTime->format("Y-m-d\TH:i:s\Z");
            $endTime = $endTime->format("Y-m-d\TH:i:s\Z");
            $response = $client->get(self::getPaypalURL("/v1/billing/subscriptions/{$subscriptionID}/transactions?start_time={$startTime}&end_time={$endTime}"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
            $body = json_decode($response->getBody()->getContents());
            return $body->transactions;
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param string $subscriptionID
     * @param string $reason
     * @throws Exception|GuzzleException
     */
    public static function cancelSubscription(string $subscriptionID, string $reason) {
        try {
            $client = new GuzzleHttp\Client();
            $client->post(self::getPaypalURL("/v1/billing/subscriptions/{$subscriptionID}/cancel"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ],
                    "json" => [
                        "reason" => $reason
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new Exception(self::getError($e, false));
        }
    }

    /**
     * @throws Exception|GuzzleException
     */
    public static function refundPayment(string $paymentID)
    {
        try {
            $client = new GuzzleHttp\Client();
            $client->post(self::getPaypalURL("/v2/payments/captures/{$paymentID}/refund"), [
                    "auth" => [Setting::get("paypal_client_id"), Setting::get("paypal_secret")],
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new Exception(self::getError($e));
        }
    }

    /**
     * @param int $value
     * @param string $unit
     * @return int
     */
    public static function getSecondsFromCycle(int $value, string $unit): int
    {
        switch ($unit) {
            case "DAY" :
                return $value * 86400;
            case "WEEK" :
                return $value * 604800;
            case "MONTH" :
                return $value * 2592000;
            case "YEAR" :
                return $value * 31536000;
            default:
                return 0;
        }
    }
}