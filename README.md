# Parspec Assignment — SQLi Demo & WAF Mitigation

Author: K.sudharsan reddy  
Instance IP: 34.194.66.116  
Date: 2025-10-04

## 1. Overview
Two pages deployed on EC2:
- page1.php — vulnerable to SQL injection (demonstration)
- page2.php — fixed (prepared statements)

## 2. How to access
SSH:
  chmod 400 ~/keys/parspec-assignment.pem
  ssh -i ~/keys/parspec-assignment.pem ubuntu@34.194.66.116

Web:
  http://34.194.66.116/page1.php
  http://34.194.66.116/page2.php

## 3. What I did (high level)
- Installed Apache, PHP, SQLite on Ubuntu EC2.
- Deployed page1 (vulnerable) and page2 (secure).
- Installed ModSecurity + OWASP CRS, tested DetectionOnly then On.

## 4. Test steps (exact)
1. Exploit page1:
   curl -i -X POST -d "username=' OR '1'='1' --&password=anything" http://34.194.66.116/page1.php
   → Expect: 302 redirect to dashboard (successful bypass)
2. Test page2:
   curl -i -X POST -d "username=' OR '1'='1' --&password=anything" http://34.194.66.116/page2.php
   → Expect: Login failed
3. Enable ModSecurity (DetectionOnly) → repeat step 1 to log detection.
4. Enable ModSecurity (On) → repeat step 1 to show blocked (403).

## 5. Proof included
- proof_page1_before_waf.txt (curl output showing bypass)
- proof_page1_after_waf.txt (curl output showing 403)
- proof_modsec_detection.txt
- proof_modsec_block.txt
- Screenshots: page1_loggedin.png, page2_failed.png, page1_blocked.png

## 6. Repo & submission
Repo: https://github.com/YOUR_GITHUB_USERNAME/parspec-assignment  
Public pages: /page1.php (exploitable), /page2.php (non-exploitable)

## 7. Notes
- demo.db contains demo credentials (plaintext) — for demo only.
- After submission: restrict SSH in Security Group to your IP.
# parspec-assignment
