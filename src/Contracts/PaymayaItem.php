<?php

namespace Coreproc\PaymayaLaravel\Contracts;

interface PaymayaItem
{
    public function paymayaItemName(): string;

    public function paymayaItemDescription(): string;

    public function paymayaItemCode(): string;

    public function paymayaItemAmount(): float;
}
