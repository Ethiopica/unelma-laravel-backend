
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">

    <!-- Static pages -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/blogs') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/products') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/services') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/career') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Dynamic blog pages -->
    @foreach($blogs as $blog)
    <url>
        <loc>{{ url("/blogs/{$blog->id}/{$blog->slug}") }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Dynamic product pages -->
    @foreach($products as $product)
    <url>
        <loc>{{ url("/products/{$product->id}/{$product->slug}") }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Dynamic service pages -->
    @foreach($services as $service)
    <url>
        <loc>{{ url("/services/{$service->id}/{$service->slug}") }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Dynamic career detail pages -->
    @foreach($careers as $career)
    <url>
        <loc>{{ url("/career/{$career->id}") }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>
