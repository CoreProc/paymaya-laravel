<?php

namespace Coreproc\PaymayaLaravel\Builders;

use CoreProc\PayMaya\Requests\Address;
use CoreProc\PayMaya\Requests\Checkout\AmountDetail;
use CoreProc\PayMaya\Requests\Checkout\Buyer;
use CoreProc\PayMaya\Requests\Checkout\Checkout;
use CoreProc\PayMaya\Requests\Checkout\Item;
use CoreProc\PayMaya\Requests\Checkout\ItemAmount;
use CoreProc\PayMaya\Requests\Checkout\TotalAmount;
use CoreProc\PayMaya\Requests\Contact;
use CoreProc\PayMaya\Requests\RedirectUrl;
use Coreproc\PaymayaLaravel\Contracts\PaymayaItem;
use Illuminate\Database\Eloquent\Model;

class PaymayaCheckoutBuilder
{
    /**
     * @var string|null
     */
    public $currency;

    /**
     * @var array
     */
    public $items;

    /**
     * @var float|null
     */
    public $discount;

    /**
     * @var float|null
     */
    public $serviceCharge;

    /**
     * @var float|null
     */
    public $shippingFee;

    /**
     * @var float|null
     */
    public $tax;

    /**
     * @var string|null
     */
    public $buyerFirstName;

    /**
     * @var string|null
     */
    public $buyerMiddleName;

    /**
     * @var string|null
     */
    public $buyerLastName;

    /**
     * @var string|null
     */
    public $buyerContactPhone;

    /**
     * @var string|null
     */
    public $buyerContactEmail;

    /**
     * @var Address|null
     */
    public $buyerShippingAddress;

    /**
     * @var Address|null
     */
    public $buyerBillingAddress;

    /**
     * @var string|null
     */
    public $referenceNumber;

    /**
     * @var string|null
     */
    public $redirectUrlSuccess;

    /**
     * @var string|null
     */
    public $redirectUrlFailure;

    /**
     * @var string|null
     */
    public $redirectUrlCancel;

    public static function make()
    {
        return new static();
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return PaymayaCheckoutBuilder
     */
    public function setCurrency(?string $currency): PaymayaCheckoutBuilder
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param PaymayaItem $paymayaItem
     * @param int $quantity
     * @return PaymayaCheckoutBuilder
     */
    public function setItem(PaymayaItem $paymayaItem, $quantity = 1): PaymayaCheckoutBuilder
    {
        if (!$paymayaItem instanceof Model) {
            throw new \ErrorException(get_class($paymayaItem).' must be a subclass of '.Model::class);
        }
        
        $itemAlreadySet = false;

        if (! empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item['model']->is($paymayaItem)) {
                    $item['quantity'] = $item['quantity'] + $quantity;
                    $itemAlreadySet = true;

                    break;
                }
            }
        }

        if (! $itemAlreadySet) {
            $this->items[] = [
                'model' => $paymayaItem,
                'quantity' => $quantity,
            ];
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float|null $discount
     * @return PaymayaCheckoutBuilder
     */
    public function setDiscount(?float $discount): PaymayaCheckoutBuilder
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getServiceCharge(): ?float
    {
        return $this->serviceCharge;
    }

    /**
     * @param float|null $serviceCharge
     * @return PaymayaCheckoutBuilder
     */
    public function setServiceCharge(?float $serviceCharge): PaymayaCheckoutBuilder
    {
        $this->serviceCharge = $serviceCharge;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getShippingFee(): ?float
    {
        return $this->shippingFee;
    }

    /**
     * @param float|null $shippingFee
     * @return PaymayaCheckoutBuilder
     */
    public function setShippingFee(?float $shippingFee): PaymayaCheckoutBuilder
    {
        $this->shippingFee = $shippingFee;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTax(): ?float
    {
        return $this->tax;
    }

    /**
     * @param float|null $tax
     * @return PaymayaCheckoutBuilder
     */
    public function setTax(?float $tax): PaymayaCheckoutBuilder
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerFirstName(): ?string
    {
        return $this->buyerFirstName;
    }

    /**
     * @param string|null $buyerFirstName
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerFirstName(?string $buyerFirstName): PaymayaCheckoutBuilder
    {
        $this->buyerFirstName = $buyerFirstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerMiddleName(): ?string
    {
        return $this->buyerMiddleName;
    }

    /**
     * @param string|null $buyerMiddleName
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerMiddleName(?string $buyerMiddleName): PaymayaCheckoutBuilder
    {
        $this->buyerMiddleName = $buyerMiddleName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerLastName(): ?string
    {
        return $this->buyerLastName;
    }

    /**
     * @param string|null $buyerLastName
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerLastName(?string $buyerLastName): PaymayaCheckoutBuilder
    {
        $this->buyerLastName = $buyerLastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerContactPhone(): ?string
    {
        return $this->buyerContactPhone;
    }

    /**
     * @param string|null $buyerContactPhone
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerContactPhone(?string $buyerContactPhone): PaymayaCheckoutBuilder
    {
        $this->buyerContactPhone = $buyerContactPhone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuyerContactEmail(): ?string
    {
        return $this->buyerContactEmail;
    }

    /**
     * @param string|null $buyerContactEmail
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerContactEmail(?string $buyerContactEmail): PaymayaCheckoutBuilder
    {
        $this->buyerContactEmail = $buyerContactEmail;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getBuyerShippingAddress(): ?Address
    {
        return $this->buyerShippingAddress;
    }

    /**
     * @param Address|null $buyerShippingAddress
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerShippingAddress(?Address $buyerShippingAddress): PaymayaCheckoutBuilder
    {
        $this->buyerShippingAddress = $buyerShippingAddress;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getBuyerBillingAddress(): ?Address
    {
        return $this->buyerBillingAddress;
    }

    /**
     * @param Address|null $buyerBillingAddress
     * @return PaymayaCheckoutBuilder
     */
    public function setBuyerBillingAddress(?Address $buyerBillingAddress): PaymayaCheckoutBuilder
    {
        $this->buyerBillingAddress = $buyerBillingAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    /**
     * @param string|null $referenceNumber
     * @return PaymayaCheckoutBuilder
     */
    public function setReferenceNumber(?string $referenceNumber): PaymayaCheckoutBuilder
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrlSuccess(): ?string
    {
        return $this->redirectUrlSuccess;
    }

    /**
     * @param string|null $redirectUrlSuccess
     * @return PaymayaCheckoutBuilder
     */
    public function setRedirectUrlSuccess(?string $redirectUrlSuccess): PaymayaCheckoutBuilder
    {
        $this->redirectUrlSuccess = $redirectUrlSuccess;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrlFailure(): ?string
    {
        return $this->redirectUrlFailure;
    }

    /**
     * @param string|null $redirectUrlFailure
     * @return PaymayaCheckoutBuilder
     */
    public function setRedirectUrlFailure(?string $redirectUrlFailure): PaymayaCheckoutBuilder
    {
        $this->redirectUrlFailure = $redirectUrlFailure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrlCancel(): ?string
    {
        return $this->redirectUrlCancel;
    }

    /**
     * @param string|null $redirectUrlCancel
     * @return PaymayaCheckoutBuilder
     */
    public function setRedirectUrlCancel(?string $redirectUrlCancel): PaymayaCheckoutBuilder
    {
        $this->redirectUrlCancel = $redirectUrlCancel;

        return $this;
    }

    /**
     * @return Checkout
     */
    public function build(): Checkout
    {
        $checkout = Checkout::make();

        $itemRequests = [];
        foreach ($this->items as $item) {
            /** @var PaymayaItem $model */
            $model = $item['model'];
            $itemRequests[] = Item::make()
                ->setName($model->paymayaItemName())
                ->setCode($model->paymayaItemCode())
                ->setDescription($model->paymayaItemDescription())
                ->setQuantity($item['quantity'])
                ->setAmount(ItemAmount::make()
                    ->setValue($model->paymayaItemAmount()))
                ->setTotalAmount(TotalAmount::make()
                    ->setValue(($model->paymayaItemAmount() * $item['quantity'])));
        }

        $checkout->setItems($itemRequests);

        $itemRequestsCollection = collect($itemRequests);

        $subtotal = $itemRequestsCollection->sum(function ($itemRequest) {
            return $itemRequest->getTotalAmount()->getValue();
        });

        $checkout->setTotalAmount(TotalAmount::make()
            ->setCurrency($this->getCurrency())
            ->setValue($subtotal -
                $this->getDiscount() +
                $this->getServiceCharge() +
                $this->getShippingFee() +
                $this->getTax())
            ->setDetails(AmountDetail::make()
                ->setDiscount($this->getDiscount())
                ->setServiceCharge($this->getServiceCharge())
                ->setShippingFee($this->getShippingFee())
                ->setTax($this->getTax())
                ->setSubtotal($subtotal)));

        $checkout->setBuyer(Buyer::make()
            ->setFirstName($this->getBuyerFirstName())
            ->setMiddleName($this->getBuyerMiddleName())
            ->setLastName($this->getBuyerLastName())
            ->setContact(Contact::make()
                ->setEmail($this->getBuyerContactEmail())
                ->setPhone($this->getBuyerContactPhone()))
            ->setShippingAddress($this->getBuyerShippingAddress())
            ->setBillingAddress($this->getBuyerBillingAddress()));

        $checkout->setRequestReferenceNumber($this->getReferenceNumber());

        $checkout->setRedirectUrl(RedirectUrl::make()
            ->setSuccess($this->getRedirectUrlSuccess())
            ->setFailure($this->getRedirectUrlFailure())
            ->setCancel($this->getRedirectUrlCancel()));

        return $checkout;
    }
}
