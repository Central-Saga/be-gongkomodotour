#!/bin/bash

echo "Testing CORS for api.gongkomodotour.com..."

# Test OPTIONS request (preflight)
echo "1. Testing OPTIONS request (preflight):"
curl -H "Origin: http://localhost:3000" \
     -H "Access-Control-Request-Method: GET" \
     -H "Access-Control-Request-Headers: Content-Type" \
     -X OPTIONS https://api.gongkomodotour.com/api/cors-test \
     -v

echo -e "\n\n2. Testing GET request:"
curl -H "Origin: http://localhost:3000" \
     https://api.gongkomodotour.com/api/cors-test \
     -v

echo -e "\n\n3. Testing with different origin:"
curl -H "Origin: https://gongkomodotour.com" \
     https://api.gongkomodotour.com/api/cors-test \
     -v
