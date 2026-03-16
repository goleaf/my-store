<?php

namespace App\Managers;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use App\Base\StorefrontSessionInterface;
use App\Models\Channel;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Contracts;

class StorefrontSessionManager implements StorefrontSessionInterface
{
    protected ?Contracts\Channel $channel = null;

    protected ?Collection $customerGroups = null;

    protected ?Contracts\Currency $currency = null;

    protected ?Contracts\Customer $customer = null;

    public function __construct(
        protected SessionManager $sessionManager,
        protected AuthManager $authManager,
    ) {
        $this->customerGroups = new Collection;

        $this->initChannel();
        $this->initCustomerGroups();
        $this->initCurrency();
        $this->initCustomer();
    }

    public function getChannel(): Contracts\Channel
    {
        return $this->channel;
    }

    public function setChannel(Contracts\Channel $channel): static
    {
        $this->sessionManager->put(
            $this->getSessionKey().'_channel',
            $channel->handle,
        );

        $this->channel = $channel;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection<\App\Models\Contracts\CustomerGroup>
     */
    public function getCustomerGroups(): Collection
    {
        return $this->customerGroups;
    }

    /**
     * @param  \Illuminate\Support\Collection<\App\Models\Contracts\CustomerGroup>  $customerGroups
     */
    public function setCustomerGroups(Collection $customerGroups): static
    {
        $this->sessionManager->put(
            $this->getSessionKey().'_customer_groups',
            $customerGroups->pluck('handle')->toArray(),
        );

        $this->customerGroups = $customerGroups;

        return $this;
    }

    public function setCustomerGroup(Contracts\CustomerGroup $customerGroup): static
    {
        return $this->setCustomerGroups(new Collection([$customerGroup]));
    }

    public function resetCustomerGroups(): static
    {
        $this->sessionManager->forget($this->getSessionKey().'_customer_groups');

        $this->customerGroups = new Collection;

        return $this;
    }

    public function getCurrency(): Contracts\Currency
    {
        return $this->currency;
    }

    public function setCurrency(Contracts\Currency $currency): static
    {
        $this->sessionManager->put(
            $this->getSessionKey().'_currency',
            $currency->code,
        );

        $this->currency = $currency;

        return $this;
    }

    public function getCustomer(): ?Contracts\Customer
    {
        return $this->customer;
    }

    public function setCustomer(Contracts\Customer $customer): static
    {
        $this->sessionManager->put(
            $this->getSessionKey().'_customer',
            $customer->id,
        );

        $this->customer = $customer;

        return $this;
    }

    public function initChannel(): void
    {
        if ($this->channel) {
            return;
        }

        $sessionChannel = $this->sessionManager->get($this->getSessionKey().'_channel');

        if ($sessionChannel) {
            $channel = Channel::query()->where('handle', $sessionChannel)->firstOrFail();

            $this->setChannel($channel);

            return;
        }

        $this->setChannel(Channel::getDefault());
    }

    public function initCustomerGroups(): void
    {
        if ($this->customerGroups->isNotEmpty()) {
            return;
        }

        $sessionCustomerGroups = new Collection(
            $this->sessionManager->get(
                $this->getSessionKey().'_customer_groups'
            )
        );

        if ($sessionCustomerGroups->isNotEmpty()) {
            $customerGroups = CustomerGroup::query()->whereIn('handle', $sessionCustomerGroups)->get();

            $this->setCustomerGroups($customerGroups);

            return;
        }

        $this->setCustomerGroup(CustomerGroup::getDefault());
    }

    public function initCurrency(): void
    {
        if ($this->currency) {
            return;
        }

        $sessionCurrency = $this->sessionManager->get($this->getSessionKey().'_currency');

        if ($sessionCurrency) {
            $currency = Currency::query()->where('code', $sessionCurrency)->firstOrFail();

            $this->setCurrency($currency);

            return;
        }

        $this->setCurrency(Currency::getDefault());
    }

    public function initCustomer(): void
    {
        if ($this->customer) {
            return;
        }

        $sessionCustomer = $this->sessionManager->get($this->getSessionKey().'_customer');

        if ($sessionCustomer) {
            $customer = Customer::query()->findOrFail($sessionCustomer);

            $this->setCustomer($customer);

            return;
        }

        if ($this->authManager->check() && is_store_user($this->authManager->user())) {
            $this->setCustomer($this->authManager->user());
        }
    }

    public function forget(): void
    {
        $this->sessionManager->forget([
            $this->getSessionKey().'_channel',
            $this->getSessionKey().'_customer_groups',
            $this->getSessionKey().'_currency',
            $this->getSessionKey().'_customer',
        ]);
    }

    public function getSessionKey(): string
    {
        return 'store_storefront';
    }
}
