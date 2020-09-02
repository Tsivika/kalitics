<?php

namespace App\Services;

use Stripe\Stripe;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class StripePayement
 *
 * @package App\Services
 */
class StripePayement
{
    /**
     * @var
     */
    private $stripe;

    /**
     * StripePayement constructor.
     *
     * @param $stripe
     */
    public function __construct(string $stripe)
    {
        $this->stripe = $stripe;
        \Stripe\Stripe::setApiKey($this->stripe);
    }

    /**
     * @return false|\Stripe\Customer
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCustomer($userMail, $stripeToken)
    {
        try {
            $customer = \Stripe\Customer::create([
                'description' => 'Paiement abonnement sur Hiboo',
                'email' => $userMail,
                'source' => $stripeToken //$_post['stripeToken']
            ]);
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return $customer;
    }

    /**
     * @return false|\Stripe\Price
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createProduct($nameProduct, $interval, $priceProduct)
    {
        try {
            $product = \Stripe\Product::create([
                'name' => $nameProduct,
                'type' => 'service',
            ]);
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        try {
            $price = \Stripe\Price::create([
                'nickname' => 'Abonnement ' . $nameProduct,
                'product' => $product->id,
                'unit_amount' => $priceProduct*100,
                'currency' => 'eur',
                'recurring' => [
                    'interval' => $interval, // month
                    'usage_type' => 'licensed',
                ],
            ]);
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return $price;
    }

    /**
     * @param $customer
     * @param $price
     * @param $coupon
     * @return false|\Stripe\Subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSubscription($customer, $price, $coupon = null)
    {
        try {
            if (is_null($coupon)) {
                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer->id,
                    'items' => [
                        ['price' => $price->id]
                    ],
                ]);
            } else {
                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer->id,
                    'items' => [
                        ['price' => $price->id]
                    ],
                    'coupon' => $coupon->id,
                ]);
            }
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return $subscription;
    }

    /**
     * @param string $percent
     * @return false|\Stripe\Coupon
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCoupon($percent = "0", $name)
    {
        try {
            $coupon = \Stripe\Coupon::create([
                'id' => $name,
                'duration' => 'once',
                'percent_off' => $percent,
            ]);
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return $coupon;
    }

}
