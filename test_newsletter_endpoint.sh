#!/bin/bash

# Test script for newsletter subscription endpoint
# Usage: ./test_newsletter_endpoint.sh

BASE_URL="${1:-http://localhost:8000}"
ENDPOINT="${BASE_URL}/api/newsletter/subscribe"

echo "Testing Newsletter Subscription Endpoint"
echo "========================================"
echo "Endpoint: ${ENDPOINT}"
echo ""

# Test 1: Missing email (should return 422)
echo "Test 1: Missing email (should return 422)"
echo "-------------------------------------------"
curl -X POST "${ENDPOINT}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{}' \
  -w "\nHTTP Status: %{http_code}\n" \
  -s | jq '.' || echo "Response received"
echo ""
echo ""

# Test 2: Invalid email (should return 422)
echo "Test 2: Invalid email format (should return 422)"
echo "-------------------------------------------------"
curl -X POST "${ENDPOINT}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "invalid-email"}' \
  -w "\nHTTP Status: %{http_code}\n" \
  -s | jq '.' || echo "Response received"
echo ""
echo ""

# Test 3: Valid email (should return 201 or 400/500 if Unelma Mail is not configured)
echo "Test 3: Valid email (should return 201 or error if Unelma Mail not configured)"
echo "------------------------------------------------------------------------------"
curl -X POST "${ENDPOINT}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "test@example.com", "first_name": "Test", "last_name": "User"}' \
  -w "\nHTTP Status: %{http_code}\n" \
  -s | jq '.' || echo "Response received"
echo ""
echo ""

echo "Tests completed!"
echo ""
echo "Expected Results:"
echo "- Test 1: HTTP 422 with message 'The email field is required.'"
echo "- Test 2: HTTP 422 with message 'The email must be a valid email address.'"
echo "- Test 3: HTTP 201 (success) or HTTP 400/500 (if Unelma Mail not configured)"












