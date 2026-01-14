# ⚠️ CRITICAL: Update Railway Environment Variables

## Problem
Your **deployed server (Railway)** is still using the **WRONG** Supabase project ID, causing `ERR_NAME_NOT_RESOLVED` errors.

## Current Situation

**Frontend is receiving URLs with:**
- ❌ `https://yxwzynfceimoymtczjtat.supabase.co/...` (WRONG - has 'y' at start, doesn't resolve)

**Should be:**
- ✅ `https://xwzynfceimoymtczjtat.supabase.co/...` (CORRECT - no 'y' at start, resolves)

## Fix Required on Railway

### Step 1: Go to Railway Dashboard
1. Visit: https://railway.app
2. Log in and select your project
3. Go to **Variables** tab

### Step 2: Update These Environment Variables

**Find and update these variables:**

```env
# OLD (WRONG) - Remove the 'y' at the start
AWS_URL=https://yxwzynfceimoymtczjtat.supabase.co/storage/v1/object/public/images
AWS_ENDPOINT=https://yxwzynfceimoymtczjtat.supabase.co/storage/v1/s3

# NEW (CORRECT) - No 'y' at start
AWS_URL=https://xwzynfceimoymtczjtat.supabase.co/storage/v1/object/public/images
AWS_ENDPOINT=https://xwzynfceimoymtczjtat.supabase.co/storage/v1/s3
```

**Important:**
- Change `yxwzynfceimoymtczjtat` to `xwzynfceimoymtczjtat`
- Remove the leading `y`
- Make sure there are NO spaces
- Make sure there are NO double dots (`..`)

### Step 3: Verify All Variables

Make sure these are set correctly:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=620907a17d9124450b4ee6d018f74947
AWS_SECRET_ACCESS_KEY=f1a557d95bdb1aeea719a40c65611e1d24a6838e94111f9bc7158fdff0a557b2
AWS_DEFAULT_REGION=auto
AWS_BUCKET=images
AWS_URL=https://xwzynfceimoymtczjtat.supabase.co/storage/v1/object/public/images
AWS_ENDPOINT=https://xwzynfceimoymtczjtat.supabase.co/storage/v1/s3
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### Step 4: Redeploy

After updating variables:
1. Railway will automatically redeploy
2. Wait for deployment to complete
3. Test the API endpoints

### Step 5: Clear Frontend Cache

After Railway redeploys:
1. **Hard refresh browser:** Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)
2. **Clear browser cache** completely
3. **Or use incognito/private mode** to test

## Verification

### Test 1: Check API Response
```bash
curl https://your-railway-app.railway.app/api/products | jq '.data[0].image_url'
```

**Expected:** Should return URL starting with `https://xwzynfceimoymtczjtat.supabase.co/...`

### Test 2: Test DNS
```bash
nslookup xwzynfceimoymtczjtat.supabase.co
```

**Expected:** Should resolve to IP addresses

### Test 3: Test Image URL
1. Get a product image URL from API
2. Paste in browser
3. Should load (if file exists in Supabase)

## Why This Happened

- Your **local** `.env` file has the correct project ID
- But your **Railway** environment variables still have the wrong one
- The frontend is calling the Railway API, which returns wrong URLs
- Local testing works, but deployed app doesn't

## Quick Checklist

- [ ] Logged into Railway dashboard
- [ ] Found Variables tab
- [ ] Updated `AWS_URL` (removed leading 'y')
- [ ] Updated `AWS_ENDPOINT` (removed leading 'y')
- [ ] Verified no double dots (`..`)
- [ ] Verified no spaces
- [ ] Waited for Railway redeploy
- [ ] Cleared browser cache
- [ ] Tested API endpoint
- [ ] Verified URLs are correct

## After Fix

Once Railway is updated and redeployed:
- ✅ API will return correct URLs
- ✅ Frontend will receive correct URLs
- ✅ Images will load (if files exist in Supabase)
- ✅ No more `ERR_NAME_NOT_RESOLVED` errors

