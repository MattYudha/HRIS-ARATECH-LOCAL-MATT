#!/bin/bash

echo "========================================="
echo "  VERIFICATION: Presence Fix Applied"
echo "========================================="
echo ""

FILE="resources/views/presences/create.blade.php"

# Test 1: Alert has d-none
echo "✓ Test 1: Alert hidden by default"
if grep -q 'id="location-warning".*d-none' "$FILE"; then
    echo "  ✅ PASS - Alert has d-none class"
else
    echo "  ❌ FAIL - Alert missing d-none class"
fi
echo ""

# Test 2: WFH/WFA cleanup logic exists
echo "✓ Test 2: WFH/WFA cleanup logic"
if grep -q "WFH/WFA: Stop camera, clear GPS" "$FILE"; then
    echo "  ✅ PASS - Cleanup logic present"
else
    echo "  ❌ FAIL - Cleanup logic missing"
fi
echo ""

# Test 3: gpsSection hide for non-WFO
echo "✓ Test 3: GPS section visibility control"
if grep -q "gpsSection.style.display = isWFO" "$FILE"; then
    echo "  ✅ PASS - GPS section toggle logic present"
else
    echo "  ❌ FAIL - GPS section toggle missing"
fi
echo ""

# Test 4: WFH/WFA fingerprint-only logic
echo "✓ Test 4: WFH/WFA fingerprint-only validation"
if grep -q "WFH/WFA Mode: Only fingerprint" "$FILE"; then
    echo "  ✅ PASS - Simplified validation for WFH/WFA"
else
    echo "  ❌ FAIL - WFH/WFA validation missing"
fi
echo ""

# Test 5: Detailed logging
echo "✓ Test 5: Enhanced debug logging"
if grep -q "Button State: FP=" "$FILE"; then
    echo "  ✅ PASS - Debug logging enhanced"
else
    echo "  ❌ FAIL - Debug logging missing"
fi
echo ""

echo "========================================="
echo "  SUMMARY"
echo "========================================="
echo "Modified file: $FILE"
echo "Backup available: $(ls -t resources/views/presences/create.blade.php.backup-* 2>/dev/null | head -1)"
echo ""
echo "📋 Next Steps:"
echo "  1. Clear browser cache (Ctrl+Shift+R)"
echo "  2. Open /presences/create"
echo "  3. Test WFO mode (all validations)"
echo "  4. Test WFH mode (fingerprint only)"
echo "  5. Test WFA mode (fingerprint only)"
echo "  6. Check debug log output"
echo ""
echo "📖 Documentation:"
echo "  - FINAL_PRESENCE_FIX.md (comprehensive guide)"
echo "  - PRESENCE_FIX_LOG.md (technical details)"
echo "  - TEST_PRESENCE_ALERT.md (test scenarios)"
echo ""
