#!/bin/bash

# Tarqumi CRM - Day 2 Test Runner
# This script runs all Day 2 CRUD tests

echo "========================================="
echo "Tarqumi CRM - Day 2 Test Suite"
echo "========================================="
echo ""

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "❌ Vendor directory not found. Running composer install..."
    composer install
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "❌ .env file not found. Copying from .env.example..."
    cp .env.example .env
    php artisan key:generate
fi

echo "🔧 Setting up test environment..."
echo ""

# Run migrations for testing
echo "📦 Running migrations..."
php artisan migrate:fresh --env=testing --force
echo ""

# Run all Day 2 tests
echo "🧪 Running Day 2 Test Suite..."
echo ""

# Run Team Management Tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1️⃣  Team Management Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan test --filter=TeamManagementTest
echo ""

# Run Client Management Tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "2️⃣  Client Management Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan test --filter=ClientManagementTest
echo ""

# Run Project Management Tests
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "3️⃣  Project Management Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan test --filter=ProjectManagementTest
echo ""

# Run all tests together with coverage (if available)
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 Full Test Suite Summary"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
php artisan test --filter="TeamManagementTest|ClientManagementTest|ProjectManagementTest"
echo ""

echo "========================================="
echo "✅ Day 2 Test Suite Complete!"
echo "========================================="
