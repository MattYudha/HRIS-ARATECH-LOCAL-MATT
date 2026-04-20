import re
import os

FILES = [
    r"c:\Users\ACER\HRIS_ARATECH\routes\web.php",
    r"c:\Users\ACER\HRIS_ARATECH\routes\web_finance.php"
]

def replace_roles(content):
    if "use App\\Constants\\Roles;" not in content:
        content = re.sub(r'(use Illuminate\\Support\\Facades\\Route;)', r'\1\nuse App\\Constants\\Roles;', content)
    
    # We will use regex to find middleware role arrays and replace the strings.
    # We'll specifically look for occurrences of Master Admin and HR Administrator.
    # It's easier to just do simple string replacements for the common patterns.
    
    patterns = {
        "'role:Master Admin'": "['role:' . Roles::MASTER_ADMIN]",
        "'role:HR Administrator,Master Admin'": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN]",
        "['role:HR Administrator,Master Admin']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN]",
        "['role:HR Administrator,Master Admin,Manager / Unit Head,Employee']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN . ',Manager / Unit Head,Employee']",
        "['role:HR Administrator,Master Admin,Manager / Unit Head']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN . ',Manager / Unit Head']",
        "['role:HR Administrator,Master Admin,inventory']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN . ',inventory']",
        "['role:HR Administrator,Master Admin,Manager / Unit Head,inventory_logs']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN . ',Manager / Unit Head,inventory_logs']",
        "['role:Master Admin,HR Administrator']": "['role:' . Roles::MASTER_ADMIN . ',' . Roles::HR_ADMINISTRATOR]",
        "['role:Master Admin,HR Administrator,Manager / Unit Head,Marketing']": "['role:' . Roles::MASTER_ADMIN . ',' . Roles::HR_ADMINISTRATOR . ',Manager / Unit Head,Marketing']",
        "['role:Master Admin,HR Administrator,Manager / Unit Head,Marketing,Supervisor']": "['role:' . Roles::MASTER_ADMIN . ',' . Roles::HR_ADMINISTRATOR . ',Manager / Unit Head,Marketing,Supervisor']",
        "['role:HR Administrator,Master Admin', 'throttle:10,1']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN, 'throttle:10,1']",
        "['role:HR Administrator,Master Admin,Manager / Unit Head,Employee', 'throttle:10,1']": "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN . ',Manager / Unit Head,Employee', 'throttle:10,1']",
    }
    
    for old, new in patterns.items():
        content = content.replace(old, new)
        
    # Specifically for web.php where some are just single string instead of array:
    content = content.replace("'role:HR Administrator,Master Admin'", "['role:' . Roles::HR_ADMINISTRATOR . ',' . Roles::MASTER_ADMIN]")

    return content

for file_path in FILES:
    if os.path.exists(file_path):
        with open(file_path, "r", encoding="utf-8") as f:
            content = f.read()
            
        new_content = replace_roles(content)
        
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(new_content)
        print(f"Updated {file_path}")
