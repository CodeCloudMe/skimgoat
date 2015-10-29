skimgoat
========

[![Join the chat at https://gitter.im/CodeCloudMe/skimgoat](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/CodeCloudMe/skimgoat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Future of News
http://www.skimgoat.com/  

All of the back-end scripts for retrieving news and responding to AJAX calls from the index.html page is in /cloud/models/news

All of the settings for your database credentials should be set in /cloud/models/main/settings.php

The database dump is locationed at /skimgoatDump.sql to get your database tables and structure all setup

You can add sources to the database by specifying the domain and catagory in `domains` table. All the rules for how to parse articles from the page should be set in `articlesParse` table, referencing the domain by the rId in the `domains` table


Better documentation coming soon.

Any questions? E-mail i@skimgoat.com or m@alinapi.com




