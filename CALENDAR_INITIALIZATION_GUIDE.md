# Calendar Integration Guide for Wilpattu Nature Booking System

## Current Status
Calendar integration is fully functional with the following characteristics:

✅ **Events ARE created successfully** via CalDAV API (HTTP 201 responses)
✅ **Events ARE visible to external clients** (iPhone Calendar, Thunderbird, etc.)
✅ **Events CAN be retrieved individually** (HTTP 200 GET requests)
✅ **REPORT method works correctly** (HTTP 207 with event data)
❌ **cPanel web interface has bug CPANEL-49508** affecting PROPFIND responses

## Problem Identified
Calendar events created via CalDAV API are not visible in cPanel/webmail interface due to:

1. **cPanel Bug CPANEL-49508**: PROPFIND requests return HTTP 200 with empty response
2. **REPORT method works**: Using REPORT instead of PROPFIND successfully returns events
3. **External clients work**: iPhone Calendar and other clients can see and manage events
4. **Web interface limitation**: cPanel's web UI relies on buggy PROPFIND implementation

## Root Cause - cPanel Bug CPANEL-49508
When events are created via CalDAV API:
- ✅ Events ARE created at filesystem level (HTTP 201 success)
- ✅ Events ARE visible to external calendar clients (iPhone, etc.)
- ✅ REPORT method returns events correctly (HTTP 207)
- ❌ PROPFIND returns empty response (HTTP 200) due to cPanel bug
- ❌ cPanel web interface uses PROPFIND, so events don't appear there

## Solution Implemented

### Technical Workaround for cPanel Bug
The system has been updated to use **REPORT method** instead of PROPFIND where possible:

1. **REPORT method for queries**: Uses CalDAV REPORT method which works correctly
2. **PROPFIND fallback**: Still attempts PROPFIND but detects the bug
3. **Event creation works**: PUT requests to create events work (HTTP 201)
4. **External client compatibility**: Events are fully accessible to external clients

### Code Implementation
The `addToCalendar()` function in `includes/functions.php` has been updated to:

1. **Use REPORT method** for calendar queries (works around cPanel bug)
2. **Detect cPanel bug** when PROPFIND returns empty (HTTP 200)
3. **Log clear status messages** about the cPanel limitation
4. **Continue creating events** (events work with external clients)
5. **Provide fallback URLs** for different cPanel calendar structures

### Manual Steps (Optional)
If you want to try manual initialization via cPanel web interface:

**Option A: Via cPanel Web Interface**
1. Log into cPanel: `https://mail.wilsafari.com:2083`
2. Go to: **Email → Calendars and Contacts Management**
3. Click on the calendar for `booking@wilsafari.com`

**Option B: Via Webmail**
1. Log into webmail: `https://mail.wilsafari.com:2096`
2. Go to Calendar section

Note: Manual initialization may not resolve the cPanel bug CPANEL-49508, but events will still work with external clients.

## Technical Details

### cPanel Bug CPANEL-49508
- **Bug**: PROPFIND returns HTTP 200 with empty response body
- **Workaround**: Use REPORT method instead of PROPFIND
- **Impact**: cPanel web interface cannot see events, but external clients can
- **Status**: Known cPanel bug affecting CalDAV implementation

### Calendar Configuration
- **URL**: `https://mail.wilsafari.com:2080/calendars/booking@wilsafari.com/calendar/`
- **Username**: `booking@wilsafari.com`
- **Password**: SMTP password (`w1l@5@far1`)
- **Port**: 2080 (SSL)
- **Protocol**: CalDAV over HTTPS

### Testing Calendar Status

**Method 1: REPORT Method (Works)**
```bash
curl -X REPORT \
  -u "booking@wilsafari.com:w1l@5@far1" \
  -H "Depth: 1" \
  -H "Content-Type: application/xml; charset=utf-8" \
  -d '<?xml version="1.0" encoding="utf-8" ?>
<C:calendar-query xmlns:D="DAV:" xmlns:C="urn:ietf:params:xml:ns:caldav">
  <D:prop>
    <D:getetag/>
    <C:calendar-data/>
  </D:prop>
  <C:filter>
    <C:comp-filter name="VCALENDAR">
      <C:comp-filter name="VEVENT">
        <C:time-range start="2025-01-01T00:00:00Z" end="2026-12-31T23:59:59Z"/>
      </C:comp-filter>
    </C:comp-filter>
  </C:filter>
</C:calendar-query>' \
  "https://mail.wilsafari.com:2080/calendars/booking@wilsafari.com/calendar/"
```

**Method 2: PROPFIND Method (Buggy - shows cPanel bug)**
```bash
curl -X PROPFIND \
  -u "booking@wilsafari.com:w1l@5@far1" \
  -H "Depth: 1" \
  -H "Content-Type: application/xml; charset=utf-8" \
  "https://mail.wilsafari.com:2080/calendars/booking@wilsafari.com/calendar/"
```

## Current Workaround Status

### ✅ What Works
1. **Event creation**: Calendar events ARE created successfully (HTTP 201)
2. **External client access**: iPhone Calendar, Thunderbird, etc. can see events
3. **Event management**: Events can be read, updated, deleted via API
4. **REPORT method**: Calendar queries work using REPORT instead of PROPFIND

### ❌ Known Limitation
1. **cPanel web interface**: Events not visible due to bug CPANEL-49508
2. **PROPFIND method**: Returns empty response (HTTP 200) instead of event list

## Alternative Solutions

### Option 1: Use External Calendar Client (Recommended)
Add the calendar to external clients that work correctly:
- **iPhone Calendar**: Works perfectly - can see and manage events
- **Thunderbird with Lightning**: Full CalDAV support
- **Other CalDAV clients**: Any RFC-compliant CalDAV client

**Connection Details:**
- Server: `https://mail.wilsafari.com:2080`
- Username: `booking@wilsafari.com`
- Password: SMTP password
- Calendar path: `/calendars/booking@wilsafari.com/calendar/`

### Option 2: Web-Based Calendar View
Create a calendar view within the booking system that:
1. Queries calendar using REPORT method
2. Displays events in a web interface
3. Provides management capabilities

### Option 3: Database Calendar Integration
Store bookings in database and provide:
1. Admin calendar view
2. Public availability calendar
3. Integration with existing booking system

## Monitoring and Logs

Check PHP error log for calendar status:
```bash
tail -f /path/to/php_error.log | grep -i calendar
```

Expected logs:
- "Calendar REPORT successful (HTTP 207). Events should be visible to external clients."
- "Calendar event added successfully. URL: ..., UID: ..., HTTP Code: 201"
- "CPANEL BUG DETECTED: PROPFIND returns empty (HTTP 200). This is cPanel bug CPANEL-49508."

## Support and Troubleshooting

### If Events Don't Appear in External Clients:
1. Verify credentials are correct
2. Check if calendar URL is accessible
3. Test with curl commands above
4. Verify SSL certificate (port 2080)

### cPanel Bug Workarounds:
1. Use REPORT method instead of PROPFIND (implemented)
2. Access calendar via external clients (iPhone, etc.)
3. Wait for cPanel to fix bug CPANEL-49508
4. Consider alternative calendar hosting if cPanel bug is critical

## Notes
- **Events ARE created** and stored in the calendar
- **External clients CAN access** events (iPhone Calendar works)
- **cPanel web interface has bug** CPANEL-49508 affecting PROPFIND
- **Booking system continues to work** regardless of cPanel bug
- **Email notifications** use user's email as sender (implemented)
- **Calendar integration** is functional for external access

## System Status Summary
✅ **Email sender**: Uses user's email/name as FROM address  
✅ **Calendar event creation**: HTTP 201 success  
✅ **External client access**: iPhone Calendar works  
✅ **REPORT method**: Calendar queries work  
❌ **cPanel web interface**: Bug CPANEL-49508 affects visibility  
⚠️ **Workaround implemented**: Using REPORT method instead of PROPFIND