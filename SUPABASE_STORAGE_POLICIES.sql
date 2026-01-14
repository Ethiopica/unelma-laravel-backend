-- Supabase Storage Policies for 'images' bucket
-- Run these in your Supabase SQL Editor
-- Format: bucket restriction + optional file/folder filters + role-based access

-- 1. Public Read Policy (for displaying images to everyone)
-- Allows anonymous and authenticated users to view images
CREATE POLICY "Public Read"
ON storage.objects
FOR SELECT
USING (
  bucket_id = 'images'
);

-- 2. Authenticated Upload Policy (for uploading images via admin panel)
-- Allows authenticated users to upload images (jpg, png, gif, webp, jpeg)
CREATE POLICY "Authenticated Upload"
ON storage.objects
FOR INSERT
WITH CHECK (
  bucket_id = 'images'
  AND (
    storage."extension"(name) IN ('jpg', 'jpeg', 'png', 'gif', 'webp')
  )
  AND auth.role() = 'authenticated'
);

-- 3. Authenticated Update Policy (for updating/replacing images)
-- Allows authenticated users to update existing images
CREATE POLICY "Authenticated Update"
ON storage.objects
FOR UPDATE
USING (
  bucket_id = 'images'
  AND auth.role() = 'authenticated'
)
WITH CHECK (
  bucket_id = 'images'
  AND (
    storage."extension"(name) IN ('jpg', 'jpeg', 'png', 'gif', 'webp')
  )
  AND auth.role() = 'authenticated'
);

-- 4. Authenticated Delete Policy (for deleting images)
-- Allows authenticated users to delete images
CREATE POLICY "Authenticated Delete"
ON storage.objects
FOR DELETE
USING (
  bucket_id = 'images'
  AND auth.role() = 'authenticated'
);

-- ============================================
-- ALTERNATIVE: More Restrictive Policies
-- ============================================
-- If you want to restrict uploads to specific folders (products, services, blogs):

-- Upload to products folder only:
-- CREATE POLICY "Authenticated Upload Products"
-- ON storage.objects
-- FOR INSERT
-- WITH CHECK (
--   bucket_id = 'images'
--   AND LOWER((storage.foldername(name))[1]) = 'products'
--   AND storage."extension"(name) IN ('jpg', 'jpeg', 'png', 'gif', 'webp')
--   AND auth.role() = 'authenticated'
-- );

-- Upload to services folder only:
-- CREATE POLICY "Authenticated Upload Services"
-- ON storage.objects
-- FOR INSERT
-- WITH CHECK (
--   bucket_id = 'images'
--   AND LOWER((storage.foldername(name))[1]) = 'services'
--   AND storage."extension"(name) IN ('jpg', 'jpeg', 'png', 'gif', 'webp')
--   AND auth.role() = 'authenticated'
-- );

-- Upload to blogs folder only:
-- CREATE POLICY "Authenticated Upload Blogs"
-- ON storage.objects
-- FOR INSERT
-- WITH CHECK (
--   bucket_id = 'images'
--   AND LOWER((storage.foldername(name))[1]) = 'blogs'
--   AND storage."extension"(name) IN ('jpg', 'jpeg', 'png', 'gif', 'webp')
--   AND auth.role() = 'authenticated'
-- );

-- ============================================
-- UTILITY QUERIES
-- ============================================

-- To check if policies exist:
-- SELECT * FROM pg_policies WHERE tablename = 'objects' AND policyname LIKE '%images%';

-- To drop all policies if needed:
-- DROP POLICY IF EXISTS "Public Read" ON storage.objects;
-- DROP POLICY IF EXISTS "Authenticated Upload" ON storage.objects;
-- DROP POLICY IF EXISTS "Authenticated Update" ON storage.objects;
-- DROP POLICY IF EXISTS "Authenticated Delete" ON storage.objects;

