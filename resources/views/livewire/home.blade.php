<div>
    <main class="max-w-screen-xl px-4 py-8 mx-auto space-y-12 sm:px-6 lg:px-8">
        <x-home.hero-slider :heroes="$this->heroes" />
        <x-home.featured-categories :categories="$this->featuredCategories" />
        <x-home.banner-grid :banners="$this->topBanners" variant="top" />
        @foreach($this->sections as $section)
            <x-home.collection-section
                :section="$section"
                :wishlist-product-ids="$this->wishlistProductIds"
            />
        @endforeach
        <x-home.banner-grid :banners="$this->middleBanners" variant="middle" />
        <x-home.banner-grid :banners="$this->bottomBanners" variant="bottom" />
        <x-home.service-features />
    </main>
</div>
