#!/bin/bash
echo "=== Stripe Webhook Diagnostic ==="
echo ""
echo "1. Checking if webhook endpoint is accessible..."
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -d '{"type":"test","data":{"object":{"id":"test"}}}' \
  -w "\nHTTP Status: %{http_code}\n" \
  -s | head -3
echo ""
echo "2. Checking recent webhook logs..."
tail -5 storage/logs/laravel.log | grep -i "webhook\|checkout" || echo "No recent webhook activity"
echo ""
echo "3. Stripe Configuration:"
php artisan tinker --execute="echo 'Mode: ' . (str_contains(config('services.stripe.secret'), 'sk_live') ? 'LIVE' : 'TEST') . PHP_EOL;"
echo ""
echo "=== Instructions ==="
echo "When making a payment from frontend:"
echo "1. Watch logs: tail -f storage/logs/laravel.log"
echo "2. Check Stripe CLI terminal for forwarded events"
echo "3. Verify frontend uses same Stripe account as backend"
