<?php

namespace App\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

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
    public function __construct($stripe)
    {
        $this->stripe = $stripe;
        \Stripe\Stripe::setApiKey($this->stripe['secret_key']);
    }

    /**
     * Create customer
     *
     * @param               $email
     * @param \Stripe\Token $token
     *
     * @return \Stripe\Customer
     */
    public function createCustomer($email, $token)
    {
        try {
            $customer = \Stripe\Customer::create(array(
                "email" => $email,
                "source" => $token,
            ));
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $customer;
    }

    /**
     * @param       $subscription
     * @param       $plan
     * @param null  $coupon
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function changeSubscription($subscription, $plan, $coupon = null)
    {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription->id);
            $subscription->plan = $plan;
            $subscription->prorate = false;
            if($coupon){
                $subscription->coupon = $coupon->id;
            }
            $subscription->save();
            if($coupon){
                $cpn = \Stripe\Coupon::retrieve($coupon->id);
                $cpn->delete();
            }
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();

        } catch
        (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $subscription
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function pauseSubcription($subscription)
    {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription->id);


        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();

        } catch
        (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param       $customer
     * @param       $plan
     * @param       $trialTimeStap
     * @param null $coupon
     *
     * @return \Stripe\Subscription
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSubscription($customer, $plan, $trialTimeStap, $coupon = null)
    {

        try {
            if (is_null($coupon)) {
                if($trialTimeStap == 0)
                    $sub = \Stripe\Subscription::create(array(
                        "customer" => $customer->id,
                        "plan" => $plan
                    ));
                else
                    $sub = \Stripe\Subscription::create(array(
                        "customer" => $customer->id,
                        "plan" => $plan,
                        "trial_end" => (int)$trialTimeStap
                    ));
            } else {
                if($trialTimeStap == 0)
                    $sub =  \Stripe\Subscription::create(array(
                        "customer" => $customer->id,
                        "plan" => $plan,
                        "coupon" => $coupon->id
                    ));
                else
                    $sub =  \Stripe\Subscription::create(array(
                        "customer" => $customer->id,
                        "plan" => $plan,
                        "coupon" => $coupon->id,
                        "trial_end" => (int)$trialTimeStap
                    ));
                // delete coupon afert subscription
                $cpn = \Stripe\Coupon::retrieve($coupon->id);
                $cpn->delete();
            }

            return $sub;

        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();

        } catch
        (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     *Charge account
     *
     * @param \Stripe\Customer  $customer
     * @param                   $amount
     * @param string            $currency
     */
    public function charge(\Stripe\Customer $customer, $amount, $currency = 'EUR')
    {
        try {
            \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $amount,
                'currency' => $currency,
            ));

        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $interval
     * @param $amount
     *
     * @return bool
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function plan($id, $interval, $amount)
    {
        try {
            \Stripe\Plan::create(array(
                    "amount" => $amount * 100,
                    "interval" => "month",
                    "interval_count" => $interval,
                    "name" => $id,
                    "currency" => "eur",
                    "id" => $id)
            );
        } catch (\Stripe\Error\ApiConnection $e) {
            return false;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $percent
     *
     * @return bool|\Stripe\Coupon
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function coupon($percent = "0")
    {
        try {
            $coupon = \Stripe\Coupon::create(array(
                "id" => "coupons",
                "duration" => "once",
                "percent_off" => $percent,
            ));
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return $coupon;
    }

    /**
     * @param $id
     *
     * @return bool
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function deletePlan($id){

        try {
            $plans =  \Stripe\Plan::all();
            foreach ($plans['data'] as $plan){
                if($plan['id'] == $id){
                    $plan = \Stripe\Plan::retrieve($id);
                    $plan->delete();
                }
            }
            return true;
        } catch (\Stripe\Error\ApiConnection $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cancel current subscription
     *
     * @return bool
     */
    public function cancel($id)
    {
        try {
            $sub = \Stripe\Subscription::retrieve($id);
            $sub->cancel(array('at_period_end' => true));
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool|\Stripe\Collection
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getAllCustomer()
    {

        try {
            $customers = \Stripe\Customer::all();
        } catch (\Stripe\Error\ApiConnection $e) {
            echo $e->getMessage();
            return false;

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return $customers;
    }

    /**
     * @param $id
     *
     * @return bool|\Stripe\Customer
     */
    public function getCustomer($id)
    {

        try {
            $customer = \Stripe\Customer::retrieve($id);
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
     * @return mixed
     */
    public function getStripe()
    {
        return $this->stripe;
    }
}
