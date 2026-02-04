#!/usr/bin/env python3
with open('app/Http/Controllers/ServiceController.php', 'r') as f:
    lines = f.readlines()

# Fix line 351 - add $currentUser before it
lines.insert(350, '        $currentUser = auth()->user();\n')

# Fix line 399 (now 400 after first insert) - add $currentUser before it  
lines.insert(399, '        $currentUser = auth()->user();\n')

# Fix line 454 (now 456 after two inserts) - add $currentUser before it
lines.insert(455, '        $currentUser = auth()->user();\n')

with open('app/Http/Controllers/ServiceController.php', 'w') as f:
    f.writelines(lines)

print("Fixed all three locations!")
