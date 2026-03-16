<div>
    @livewire(
        $getContent(),
        [
            'record' => $getRecord(),
        ],
        key('store_livewire_'.$getContentName())
    )
</div>