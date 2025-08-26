# Tab Switching Prevention Feature

## Overview
This feature prevents students from cheating during exams by detecting and preventing tab switching, use of developer tools, and other suspicious activities. The system automatically monitors exam sessions and takes appropriate action when violations are detected.

## Features

### 1. **Tab Switching Detection**
- Detects when students switch browser tabs
- Monitors window focus changes
- Tracks visibility changes

### 2. **Developer Tools Prevention**
- Blocks F12 key (Developer Tools)
- Prevents Ctrl+Shift+I (Inspect Element)
- Blocks Ctrl+U (View Source)
- Prevents Ctrl+Shift+C (Element Inspector)
- Blocks Ctrl+Shift+J (Console)

### 3. **Navigation Prevention**
- Prevents Alt+Tab (Window switching)
- Blocks Ctrl+Tab (Tab switching)
- Disables Windows key
- Prevents page refresh (Ctrl+R, F5)

### 4. **Fullscreen Enforcement**
- Forces fullscreen mode during exams
- Detects when user exits fullscreen
- Automatically attempts to return to fullscreen

### 5. **Context Menu Blocking**
- Prevents right-click context menu
- Blocks copy/paste operations

### 6. **Violation Tracking**
- Records all violation attempts
- Tracks violation count per student
- Logs detailed violation information

### 7. **Automatic Exam Termination**
- Terminates exam after 3 violations
- Saves current progress before termination
- Shows detailed termination notice

## Installation

1. **Run the Installation Script**
   ```
   http://localhost/your-project/install_tab_monitoring.php
   ```

2. **Or Execute SQL Manually**
   - Run `create_tab_violations_table.sql`
   - Run `add_termination_tracking.sql`

## Database Tables

### `tab_violations`
Stores all tab switching and security violations:
- `id` - Unique violation ID
- `email` - Student email
- `name` - Student name
- `violation_type` - Type of violation
- `violation_count` - Current violation count
- `timestamp` - When violation occurred
- `exam_id` - Exam identifier
- `question_number` - Current question

### `exam_terminations`
Logs exam termination events:
- `email` - Student email
- `exam_id` - Exam identifier
- `termination_reason` - Why exam was terminated
- `violation_count` - Total violations
- `timestamp` - Termination time

### Modified `history` table
Added columns:
- `terminated` - Whether exam was terminated (0/1)
- `termination_reason` - Reason for termination

## How It Works

### For Students

1. **Exam Start**
   - Security notification is shown
   - Fullscreen mode is activated
   - Tab monitoring begins

2. **During Exam**
   - All suspicious activities are monitored
   - Violations trigger immediate warnings
   - Progressive warning system (1st, 2nd, 3rd warning)

3. **Violation Warnings**
   - **1st Warning**: "Stay focused on your exam"
   - **2nd Warning**: "One more violation will terminate your exam"
   - **3rd Violation**: Automatic exam termination

4. **Exam Termination**
   - Current answers are saved
   - Student is redirected to results page
   - Termination notice is displayed

### For Administrators

1. **Violation Monitoring**
   - Access via Dashboard → Tab Violations
   - View real-time violation statistics
   - Filter violations by type, user, exam

2. **Statistics Available**
   - Total violations
   - Today's violations
   - Terminated exams
   - Unique violators

3. **Violation Types Tracked**
   - Tab switching/window minimizing
   - Developer tools attempts
   - Context menu attempts
   - Window focus loss
   - Fullscreen exits
   - Keyboard shortcut attempts

## Configuration

### Violation Threshold
Currently set to 3 violations before termination. To change:

1. Edit `account.php`
2. Find: `if (tabSwitchWarnings >= 3)`
3. Change number as needed

### Violation Types
Add new violation types by:

1. Adding detection logic in `enableTabSwitchDetection()`
2. Calling `recordTabSwitchViolation()` with new type
3. Updating admin filters in `dash.php`

## Files Modified/Added

### New Files
- `save_tab_violation.php` - Handles violation logging
- `create_tab_violations_table.sql` - Database schema
- `add_termination_tracking.sql` - History table updates
- `install_tab_monitoring.php` - Installation script

### Modified Files
- `account.php` - Added detection logic and UI updates
- `update.php` - Added termination handling
- `dash.php` - Added admin monitoring interface

## Security Considerations

1. **Client-Side Detection**: Most detection is client-side and can be bypassed by advanced users
2. **JavaScript Dependency**: Requires JavaScript to be enabled
3. **Browser Compatibility**: Works best in modern browsers
4. **False Positives**: Some legitimate actions may trigger violations

## Troubleshooting

### Common Issues

1. **Fullscreen Not Working**
   - Check browser permissions
   - Some browsers block automated fullscreen requests

2. **High False Positives**
   - Adjust violation thresholds
   - Review violation types
   - Consider browser compatibility

3. **Database Errors**
   - Ensure tables are created properly
   - Check database permissions
   - Run installation script again

### Debug Mode
To enable debugging, add to JavaScript console:
```javascript
// Enable debug logging
localStorage.setItem('tabMonitoringDebug', 'true');
```

## Best Practices

1. **Student Education**
   - Inform students about monitoring
   - Provide clear exam guidelines
   - Test system before actual exams

2. **Admin Monitoring**
   - Regular violation review
   - Investigate patterns
   - Handle false positives appropriately

3. **System Testing**
   - Test with different browsers
   - Verify violation thresholds
   - Check termination procedures

## Future Enhancements

Possible improvements:
- Advanced screen monitoring
- Biometric verification
- Network traffic analysis
- Mobile device detection
- AI-powered behavior analysis

## Support

For issues or questions:
1. Check violation logs in admin dashboard
2. Review database tables for data integrity
3. Test with different browsers and scenarios
4. Contact system administrator for advanced troubleshooting
