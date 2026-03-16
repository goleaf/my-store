@props([])

<section class="grid grid-cols-1 gap-6 border-y border-slate-100 py-12 sm:grid-cols-2 lg:grid-cols-4">
    <div class="flex items-start gap-4 rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
            <x-lucide-clock class="h-7 w-7" />
        </div>
        <div class="space-y-1">
            <h4 class="text-sm font-bold text-slate-900">10 minute grocery now</h4>
            <p class="text-xs leading-6 text-slate-500">Get your order delivered to your doorstep at the earliest from pickup stores near you.</p>
        </div>
    </div>

    <div class="flex items-start gap-4 rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <div class="rounded-2xl bg-amber-50 p-3 text-amber-600">
            <x-lucide-gift class="h-7 w-7" />
        </div>
        <div class="space-y-1">
            <h4 class="text-sm font-bold text-slate-900">Best prices and offers</h4>
            <p class="text-xs leading-6 text-slate-500">Better everyday pricing and storewide offers make repeat ordering feel worth it.</p>
        </div>
    </div>

    <div class="flex items-start gap-4 rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <div class="rounded-2xl bg-sky-50 p-3 text-sky-600">
            <x-lucide-package class="h-7 w-7" />
        </div>
        <div class="space-y-1">
            <h4 class="text-sm font-bold text-slate-900">Wide assortment</h4>
            <p class="text-xs leading-6 text-slate-500">Choose from pantry staples, fresh produce, bakery items, and household essentials.</p>
        </div>
    </div>

    <div class="flex items-start gap-4 rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <div class="rounded-2xl bg-rose-50 p-3 text-rose-600">
            <x-lucide-rotate-ccw class="h-7 w-7" />
        </div>
        <div class="space-y-1">
            <h4 class="text-sm font-bold text-slate-900">Easy returns</h4>
            <p class="text-xs leading-6 text-slate-500">Need to swap something out? Our return process is quick, clear, and customer-friendly.</p>
        </div>
    </div>
</section>
