# Ramadan Donation Portal — Project Specification

## 1. Project Overview
This is **Ramadan Donation Portal** for surau/community fundraising  
Base domain: **ramadan.farahana.com**

**Goal:**  
A lightweight web portal where:
- Organizers can create donation campaigns.
- Donors can declare donation amounts.
- Publicly track progress and history.

No payment gateway required at first — donors pay manually via QR codes.

---

## 2. Stack
- Laravel (PHP)
- MySQL
- Blade templates
- Tailwind CSS (optional for UI)

---

## 3. Features

### 3.1 Campaigns (Organizer)
- Create Campaign
- Upload QR image
- Enter:
  - Title
  - Description
  - Target amount
  - Deadline
- View total collected
- Campaign status (active/closed)

---

## 3.2 Donations (Donor)
- Public campaign listing
- For each campaign:
  - Donation form: name, mobile (optional), donation amount
- Store donation entries
- Display:
  - Campaign total raised
  - Donor total contribution (per session/email if logged in)

---

## 4. Authentication
- Simple admin login for organizers
- No login for donors by default

---

## 5. UI Wireframes (Blade)

### 5.1 Home / Campaign Listing

[Navbar]
Title: Ramadan Donation Portal

Campaign Card:

Campaign title

Description excerpt

Progress bar

Target / Collected

“Donate” button


### 5.2 Campaign Detail

Campaign title
QR image
Progress: RM X / RM Y
Donation Form:

Name

Amount

Submit button


### 5.3 Admin Dashboard

List of campaigns
Add campaign button
Each campaign:

Edit

View donors


---

## 6. Database Schema

### campaigns
| Field | Type | Notes |
|-------|------|-------|
| id | bigIncrements |
| title | string |
| description | text |
| target_amount | decimal(10,2) |
| deadline | date |
| qr_image | string |
| created_at | timestamp |
| updated_at | timestamp |

### donations
| Field | Type | Notes |
| id | bigIncrements |
| campaign_id | foreign key |
| donor_name | string |
| donor_mobile | string (optional) |
| amount | decimal(10,2) |
| created_at | timestamp |

---

## 7. Routes


GET / => campaign index
GET /campaign/{id} => show campaign
POST /campaign/{id}/donate => store donation

GET /admin => admin dashboard
GET /admin/campaign/create => form
POST /admin/campaign => store
GET /admin/campaign/{id}/edit => edit
PUT /admin/campaign/{id} => update


---

## 8. Controllers to Create

1. `CampaignController`
   - index()
   - show()
   
2. `DonationController`
   - store()

3. `Admin\CampaignController`
   - index
   - create
   - store
   - edit
   - update

---

## 9. Validation Rules

- Campaign: title required, target numeric, deadline future date
- Donation: amount required, numeric, > 0

---

## 10. UI & UX

- Minimal, clean pages
- Use Tailwind or simple CSS
- Progress bar on campaign detail (optional)

---

## 11. Deployment
- Build locally
- Upload to Hostinger
- Configure `.env` with DB credentials
- Serve via subdomain `ramadan.farahana.com`