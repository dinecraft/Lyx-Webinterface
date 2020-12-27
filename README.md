
# TODO  
## Frontent Management
- Designing Tool - Build custom site easy (own rendering Engine in work)
- Payment System - Dynmicly add Payment Methods to the Payment Process
- Different Payment-Types |Free, One-Time, Interval, Prepaid, Custom
- Frontend-API Registering Frontend-Plugins
- Roles system: Superadmin, Admin, Supporter, Reseller, User - and custom group Support
- CMS System: manage Images dynmaicly
- custom Language Packets
## Dev APIs
- Interact with everything over API (Rest API HTtp(s)) JSON
- Custom routes (set default routes by Plugin - setup synonymous Routes.
- Interact with the CSS Rules for styling Plugins
- AJAX API Load dynamicly Contents
- 



# OpenHCP - Open Hosting Control Platform

OpenHCP is an open Source project which is Maintained by me. It's an open source alternative
to Solutions like WHMCS or Tekbase. The System comes with a full featured Stack of Functions, 
a multilevel Permission System, a Plugin System, a Ticketing System and a diverse Theming and 
Designing System. This Project is not for somebody who needs a High secure never have problems -
Panel, only for them who never pay for WHMCS or like open source Projects.

### **<span style="color:#d95353;">Problems: The Panel is not working yet. Its under developement, so never use it - when you're not a Dev :)</span>**

## Features List:
- Open Source, no "**Freemium**".
- You can use it for Personal and Commercial use.
- It's based on **Laravel** Framework (7). Which is easy extendable and good documented.
- Easy to Setup (need only Webserver and MySQl Database).
- **Plugin/Module System** - you can write your **own** Plugins and integrate it easy into Panel.
- Well documented Developer and User Documentation (//TODO).
- **Permissions System** with Roles + Single extra Set-able Permissions per User/group.
- Backend System for the Controllpanel.
- Frontend System, design your Site without editing a Stack of Files in Webserver.
- Extended Theming System. (With own Rendering Engine, called **PineapplePen**).
- **Ticketing System** for Supporters/Admins (Require an **SMTP** and **IMAP** Server).
- **API** Included. You can perform a lot of Functions directly per REST API (JSON).
- AJAX Support for dynamically Loading Sites smooth.  
- Few Plugins included - like **Proxmox** Plugin (for VPS), or **KeyHelp** Plugin for Webhosting.  
- Theme Marketplace & Plugin Marketplace (few click installer).

  
## TODO:
- Plesk Plugin
- KeyHelp Plugin
- Proxmox Plugin
- Ticketing Plugin
- Domain Plugin
- Developer Documentation
- User Documentation


## How to make my own Plugin?
You could use the "create Plugin" in ***Admin > Settings > Plugins***. Thats create a new "default Plugin",
with the Default Plugin Folder Structure:
```
    - myPluginName
      - Handler.php //file wich is the 'Handshake' between Panel and Plugin
      - Main
        - controllers //folder where all Controllers/PHP functions/classes stored.
            - myController.php // ...
        - frontend
            - css
                - css
                - js
                - images
            - LangFiles
                - en
                    - en.json
                - de
                    - de.json
            - Views
                - index.view.html //also blade support is given with .blade.php
        - internal
            - router.php 
            - render.php
        - routing
            - web.php //define the routes for the plugin
```
