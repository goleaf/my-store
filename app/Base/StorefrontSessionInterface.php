<?php

namespace App\Base;

use Illuminate\Support\Collection;
use App\Models\Contracts\Channel;
use App\Models\Contracts\Currency;
use App\Models\Contracts\Customer;
use App\Models\Contracts\CustomerGroup;

interface StorefrontSessionInterface
{
    public function getChannel(): Channel;

    public function setChannel(Channel $channel): static;

    public function getCustomerGroups(): ?Collection;

    public function setCustomerGroups(Collection $customerGroups): static;

    public function setCustomerGroup(CustomerGroup $customerGroup): static;

    public function getCurrency(): Currency;

    public function setCurrency(Currency $currency): static;

    public function getCustomer(): ?Customer;

    public function setCustomer(Customer $customer): static;

    public function getSessionKey(): string;
}
