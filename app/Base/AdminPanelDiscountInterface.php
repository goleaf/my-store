<?php

namespace App\Base;

interface AdminPanelDiscountInterface
{
    /**
     * Return the schema to use in the admin panel
     */
    public function adminPanelSchema(): array;

    /**
     * Mutate the model data before displaying it in the admin form.
     */
    public function adminPanelOnFill(array $data): array;

    /**
     * Mutate the form data before saving it to the discount model.
     */
    public function adminPanelOnSave(array $data): array;

    /**
     * Define any relation managers you want to add to the admin form.
     */
    public function adminPanelRelationManagers(): array;
}
