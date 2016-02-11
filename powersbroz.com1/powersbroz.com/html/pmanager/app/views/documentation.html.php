<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Entity {CC} <?= $app->version;?> Documentation</h2>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h2>Table of Contents</h2>
                <div class="glass">
                    <h3>The Quick Menu</h3>
                    <ul class="list-unstyled">
                        <li>How to Access</li>
                    </ul>
                    <h3>Installation &amp; Setup</h3>
                    <ul class="list-unstyled">
                        <li><a href="#install-txt">The installation.txt file</a></li>
                        <li><a href="#config-file">The config.php file</a></li>
                    </ul>
                    <h3>Users</h3>
                    <ul class="list-unstyled">
                        <li><a href="#access-roles">Access roles</a></li>
                        <li><a href="#creating-users">Creating</a></li>
                        <li><a href="#editing-users">Editing</a></li>
                        <li><a href="#deleting-users">Deleting</a></li>
                    </ul>
                    <h3>Groups</h3>
                    <ul class="list-unstyled">
                        <li><a href="#about-groups">About Groups</a></li>
                        <li><a href="#group-owners">Group Owners</a></li>
                        <li><a href="#creating-groups">Creating</a></li>
                        <li><a href="#group-features">Group Features</a></li>
                    </ul>
                    <h3>Projects</h3>
                    <ul class="list-unstyled">
                        <li><a href="#about-projects">About Projects</a></li>
                        <li><a href="#stages-tasks">Stages and Tasks</a></li>
                        <li><a href="#tracking-time">Tracking time</a></li>
                        <li><a href="#project-forum">The Project Forum</a></li>
                        <li><a href="#creating-projects">Creating</a></li>
                        <li><a href="#editing-projects">Editing</a></li>
                        <li><a href="#deleting-projects">Deleting</a></li>
                        <li><a href="#project-access">Access</a></li>
                    </ul>
                    <h3>Files</h3>
                    <ul class="list-unstyled">
                        <li><a href="#creating-files">Creating</a></li>
                        <li><a href="#deleting-files">Deleting</a></li>
                        <li><a href="#file-storage">Storage</a></li>
                    </ul>
                    <h3>Invoices</h3>
                    <ul class="list-unstyled">
                        <li><a href="#creating-invoices">Creating</a></li>
                        <li><a href="#editing-invoices">Editing</a></li>
                        <li><a href="#deleting-invoices">Deleting</a></li>
                        <li><a href="#invoice-access">Access</a></li>
                        <li><a href="#invoice-payment">Payment</a></li>
                    </ul>
                    <h3>Wages &amp; Taxes</h3>
                    <ul class="list-unstyled">
                        <li><a href="#about-wages">About Wages</a></li>
                        <li><a href="#creating-wages">Creating Wages</a></li>
                        <li><a href="#editing-wages">Editing Wages</a></li>
                        <li><a href="#deleting-wages">Deleting Wages</a></li>
                        <li><a href="#about-taxes">About Taxes</a></li>
                        <li><a href="#creating-taxes">Creating Taxes</a></li>
                        <li><a href="#editing-taxes">Editing Taxes</a></li>
                        <li><a href="#deleting-taxes">Deleting Taxes</a></li>
                        <li><a href="#applying-wages-taxes">Applying Wages and Taxes</a></li>
                    </ul>
                    <h3>Payment Collection</h3>
                    <ul class="list-unstyled">
                        <li><a href="#company-stripe">Company Stripe Account</a></li>
                        <li><a href="#user-stripe">User Stripe Accounts</a></li>
                        <li><a href="#company-paypal">Company Paypal Account</a></li>
                        <li><a href="#user-paypal">User PayPal Accounts</a></li>
                    </ul>
                    <h3>The Calendar <sup style="font-size: 10px;" class="label label-warning">Coming soon</sup></h3>
                    <ul class="list-unstyled">
                        <li>Usage</li>
                    </ul>
                    <h3>Application Settings</h3>
                    <ul class="list-unstyled">
                        <li><a href="#company-information">Company Information</a></li>
                        <li><a href="#login-configuration">Login Configuration</a></li>
                        <li><a href="#email-notifcations">Email Notifications</a></li>
                        <li><a href="#downtime-settings">Downtime &amp; Maintenance</a></li>
                    </ul>
                    <h3>The Activity Log</h3>
                    <ul class="list-unstyled">
                        <li><a href="#cleaning-log">Cleaning the Log</a></li>
                        <li><a href="#erasing-log">Erasing the log</a></li>
                    </ul>
                    <h3>Translation <sup style="font-size: 10px;" class="label label-warning">Coming soon</sup></h3>
                    <ul class="list-unstyled">
                        <li><a href="#adding-translation">Adding a Translation File</a></li>
                    </ul>
                    <h3>Successful usage</h3>
                    <ul class="list-unstyled">
                        <li><a href="#customization">Customization</a></li>
                        <li><a href="#semantic-versioning">Semantic Versioning</a></li>
                        <li><a href="#support">Support</a></li>
                        <li><a href="#no-nos">No nos</a></li>
                    </ul>
                    <h3>Product Links</h3>
                    <ul class="list-unstyled">
                        <li><a href="http://www.zenperfectdesign.com/register/" target="_blank" title="Entity {CC} Official Product Page">Register</a></li>
                        <li><a href="http://www.zenperfectdesign.com/products/entity/" target="_blank" title="Entity {CC} Official Product Page">Product Page</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">

                <h2 class="setting-group">Product roadmap - new features and bugs</h2>
                <h3>Documentation and Support</h3>
                <p>Thanks for buying Entity {CC}! This documentation is designed to help you learn how to use the application and really maximize its benefits. New features and bug fixes will be released periodically as minor and patch version upgrades. If you want to know more about my plans for future releases, be sure to visit my <a href="http://www.zenperfectdesign.com/products/#roadmap" target="_blank" title="Zen Perfect Design product roadmap">product roadmap</a>.</p>
                <h3>New Version</h3>
                <p>Keep in mind this is a complete recreation of Entity and you can expect to find minor bugs here and there. The quicker that you let me know of these issues, the quicker I'll make sure they go away. This new version brings a huge and powerful custom role system which introduced a whole new level of complexity. In addition there are many features still planned for the immediate future and as they are introduced, there will be minor updates to workflow and the graphical user interface. Your feedback is highly valued and in the end I want to make sure that this application lives up to your expectations. My email is at the bottom of this documentation. Feel free to shoot me a direct line to let me know what you think.</p>
                <!-- The Quick Menu -->
                <h2 class="setting-group">The Quick Menu</h2>
                <h3>How to Access</h3>
                <p>You can access the quick menu from anywhere in the application by clicking the (`/~) key on your key board. The quick menu is just as it says: a "quick" way to get around the application without trudging through multiple menus.</p>
                <p class="alert alert-info">The quickmenu is only available to users who are marked as staff. I still have a lot of work to do on the quickmenu, so expect a lot of changes in future releases.</p>
                <!-- Installation and Setup -->
                <h2 class="setting-group">Installation &amp; Setup</h2>
                <h3><a id="install-txt"></a> The installation.txt file</h3>
                <p>The installation.txt file is found in the downloaded .zip file from CodeCanyon. Inside this file is an outline of the steps you must take to get Entity up and running. Be sure to revisit this file when making upgrades, as important information will be placed in there that might change due to improvements or added features.</p>
                <h3><a id="config-file"></a> The config.php file</h3>
                <p>The config.php file is located in the app folder. This file is responsible for hard coded settings such as your database connection that will never change, or change very infrequently. This file is very important. If it is misconfigured, there will be a lot of errors, and in some cases you may see a blank white screen, which indicates a PHP Fatal error.</p>
                <p>Most errors users encounter are based on a misconfigured config.php file. If the CSS is not loading correctly or you see nothing on the screen then more than likely, you need to check your config.php file closely. Most of the time it has to do with your directory settings in the: <code>BASE_URL</code> and <code>ROOT_URL</code> variables.</p>
                <!-- Users -->
                <h2 class="setting-group">Users</h2>
                <h3><a id="access-roles"></a> Access Roles</h3>
                <p>I've provided you with five access role templates:</p>
                <div class="well well-lg">
                    <ul class="list-unstyled">
                        <li><i class="glyphicon glyphicon-user"></i> The System Administrator</li>
                        <li><i class="glyphicon glyphicon-user"></i> The Project Lead</li>
                        <li><i class="glyphicon glyphicon-user"></i> The Employee</li>
                        <li><i class="glyphicon glyphicon-user"></i> The Contractor</li>
                        <li><i class="glyphicon glyphicon-user"></i> The Client / Customer</li>
                    </ul>
                </div>
                <p><a id="owner-account"></a> In addition to these default access role templates, you can also define your own access roles using privileges that you set manually. There is also a unique <span class="label label-primary">owner account</span>, which although not an access role, it is the account that the system uses to notify when certain events happen in Entity. There can only be one owner access account, but it can be changed whenever you like.</p>
                <p class="alert alert-warning">If you delete an access role manually from the database, you will run into numerous errors. Therefore, don't do it! <i class="fa fa-smile-o"></i></p>
                <h4>System Administrator</h4>
                <p>The system administrator can access all parts of Entity {CC}. This role should be used sparingly and you should always favor less over more when it comes to control of your business.</p>
                <h4>Project Lead</h4>
                <p>Project leads are like project managers. When it comes to the project, they can do anything. They cannot, however, delete users, edit user accounts, or anything else outside the scope of the project they are a lead on. They can, however, assign users to projects and remove assignments.</p>
                <h4>Employee</h4>
                <p>Employees can move between projects, work on tasks, update them, upload briefs and files for the project, but they cannot see or edit other user information. They cannot delete or make widespread changes to projects like the Project Lead can.</p>
                <h4>The Contractor</h4>
                <p>The Contractor is a role that must be assigned to a project to access the system. This role is for people you might hire to help out on a project, but they will not have access to your client base or your employees. They communicate with employees only through the project messaging system. They can create invoices, billed to staff (not clients).</p>
                <h4>The Client / Customer</h4>
                <p>Clients are the lowest on the totem pole in terms of access. They have access only to view progress and make comments on the message board. They can only send messages to staff. They cannot create invoices but they do pay them.</p>
                <h3><a id="creating-users"></a> Creating</h3>
                <p>You can create a user from the dashboard or from within the <span class="label label-info"><a href="<?= BASE_URL;?>users/" title="All users"><i class="fa fa-link"></i> user account overview page</a></span>. Creating a user is quick and simple. Users can flesh out their own information later or you can manually edit it yourself from their user detail page. The most important part of creating a user is what role you assign them. Entity 2.0.x allows you powerful control over access roles. With this power you need to make sure you assign your users in a smart and cautious way.</p>
                <p><a id="primary-roles"></a>While access roles help you really get in and customize what users can do and see, the <span class="label label-info">primary role</span> is one of the most important aspects of creating a new role. There are three available primary roles to assign an access role to:</p>
                <dl>
                    <dt>Staff</dt>
                    <dd>This association should be reserved for you and people who work with or for you.</dd>
                    <dt>Contractors</dt>
                    <dd>This association is for third party contractors who you might use to do certain jobs for you like design or development that you currently do not offer. If you are a one-man show, or you don't use third parties, you need not ever create a contractor role.</dd>
                    <dt>Client</dt>
                    <dd>These are the people who you do work for. Clients have very limited access to certain areas of the application and you should be very hesitant to start granting them access to sensitive application settings, payment configuration, and more.</dd>
                </dl>
                <h3><a id="editing-users"></a> Editing</h3>
                <p>You can edit a user from their user detail page. It can be accessed from the <span class="label label-info"><a href="<?= BASE_URL;?>users/" title="All users"><i class="fa fa-link"></i> user account overview page</a></span></p>
                <h3><a id="deleting-users"></a> Deleting</h3>
                <p>Deleting users is highly discouraged. Entity's database structure makes use of optimized relationships and deleting a user will create a deletion cascade on all the items related to that user's account. This is bad on so many levels. If you like to maintain accurate historical records, deleting user accounts will cause gaps and provide you with inaccurate reports. It is much better to change your display settings to hide user accounts which you have <span class="label label-primary">deactivated</span>.</p>
                <p>It is really easy to deactivate a user's account. When a user's account is deactivated they cannot login or access the system. You can then change your user view preferences and hide them all together to hide a potentially massive client list, most of which might be old clients which don't need to access the system anymore.</p>

                <!-- Groups -->
                <h2 class="setting-group">Groups</h2>
                <h3><a id="about-groups"></a> About Groups</h3>
                <p>Groups are nothing more than a way to organize your users. Each group has access to their assigned group page and can communicate with each other from that page. There are no group login accounts and invoices are assigned to users, not groups. Each group has it's own forum, notes, messaging system, and files. Groups are a tool to help compartmentalize user access. If you are working with a company on a project, then it would probably be best to create a group for that company and assign users to the group. In this way, you can communicate with them, and they can communicate with each other.</p>
                <h3><a id="group-owners"></a> Group Owners</h3>
                <p>You can assign groups an owner to manage the groups information, roster, etc. The group owner will likely need privileges to access your Entity {CC} addressbook to make that role effective. Administrators and custom roles with the full address book privilege can be effective owners. Owners make sure that stuff stays on track in small teams and can edit the group's page.</p>
                <h3><a id="creating-groups"></a> Creating</h3>
                <p>You can create groups by visiting the <span class="label label-info"><a href="<?= BASE_URL;?>groups/" title="All groups"><i class="fa fa-link"></i> groups overview page</a></span> and clicking on the little <span class="label label-success"><i class="fa fa-plus"></i></span> button at the top of the page.</p>
                <h3><a id="group-features"></a> Group Features</h3>
                <p>I am working on new ways to make grouping your users a powerful and efficient means of managing teams. I already have ideas in the works ot allow file sharing and other important collaboration tools to the group's page. Keep any eye out for new features and be sure to feed in your ideas.</p>
                <!-- Projects -->
                <h2 class="setting-group">Projects</h2>
                <h3><a id="about-projects"></a> About Projects</h3>
                <p>Projects can be considered a group of tasks and assignments where you, your team, and clients are working toward some end goal or product. You can appropriate projects as jobs, internal collaborations, etc. The use is entirely up to you! Projects are flexible "containers" for you to organize an end goal into something that can be managed through time.</p>
                <p>Projects must have a start and end date. On the project page this progression of time is represented as a meter showing you where you "should" be at at the date and time you access the project's page. If the project hasn't started yet, you will see a message letting you know that the meter will be shown once the project date arrives. If the project is overdue, you will see a red meter and the number of days overdue the project is. You can always edit a project's start and end date by clicking on the <span class="label label-warning"><i class="fa fa-edit"></i> Edit</span> button at the top of the project's page.</p>
                <p class="alert alert-info">Keep in mind that dates in PHP start at 00:00 which is 12AM. This might be confusing, but time will cause the meter to progress. So, the first day will always show some progression and the last day will show the project as being overdue.</p>
                <h3><a id="stages-tasks"></a> Stages &amp; Tasks</h3>
                <p><span class="label label-primary">Stages</span> are like milestones in your project. They also help you, your team, and your clients get a visual idea of how the project should be progressing. They are the primary organizational tool to split your projects up into manageable chunks. You can further divide and organize your stages using <span class="label label-primary">staged tasks</span>, which divide up the work of the stage into smaller portions.</p>
                <p>You can assign staged tasks to multiple users. Each user will be able to work on the task, and create notes for their assigned tasks to share with other members responsible for getting the task done. You can also create un-staged tasks which are assigned to the project as a whole. This kind of task is often very good for something which needs to get done, but doesn't really fit into any one category. It is also good for those last minute things you need to get done ASAP.</p>
                <p class="alert alert-info">Project tasks have not yet been implemented in version 2.0.0</p>
                <p>Stages can, optionally, have a <span class="label label-info">stage brief</span> assigned to them. The stage brief is a document such as a PDF, Word document, or text file uploaded by the project lead that covers in detail what needs to be done during this stage of your project. Only the project lead can upload and update a stage brief.</p>
                <p>Staged tasks follow this file-based philosophy with <span class="label label-info">task attachments</span>. Users assigned to a task can attach files that are already uploaded or even upload a new file while in the project page. Attachments are files which will be stored in that user's "my files" area and they do count towards that user's total allowed file space.</p>
                <p>Stage <span class="label label-info">task notes</span> are quick notes that user's assigned to a task can create to share progress updates, snippets, ideas, etc - you name it to communicate with each other and the project lead as everyone works together to get things done without posting things on the project's forum. The good thing about notes is that they offer you a concentrated way to keep information about a task in one place.</p>
                <p class="alert alert-warning">Stage task notes cannot be edited or deleted.</p>
                <p><span class="label label-success">Closing a stage</span> will mark that stage as complete but will leave all children tasks unaltered so you can get a snapshot of your progress upon completion. You will not be able to edit or add anything to children tasks, but the statistics will provide you valuable feedback later in other reports. You may, for instance, want to know how good you or your team is at getting things done in a provided amount of time.</p>
                <p>You can <span class="label label-info">move stage tasks</span> to other stages in the project, or even unstage them as a task for the project itself, by simply clicking on the <span class="label label-warning"><i class="fa fa-arrows"></i> switch stage</span> button.</p>
                <h3><a id="tracking-time"></a>Tracking time</h3>
                <p>It's always nice to keep yourself on track with a timer. Entity {CC} uses a javascript-based timer for wage calculation. All you have to do is click the start timer button on the project page and you can log time to your project work. When you are finished, you can save your time to the database and use it later when creating charges for an invoice.</p>
                <p>Once you start the timer, it will open in a small window with a message letting you know that your time is being tracked. Do not close this window! Once you are finished, you can select I'm done in the window dialogue, and assign the time to a wage or just write a quick description of what happened during that time as a reminder for later.</p>
                <h3><a id="project-forum"></a>The Project Forum</h3>
                <p>Every project you create in Entity {CC} will generate a project forum. Here users assigned to the project can talk openly with each other and leave messages which other users can reply to.</p>
                <h3><a id="creating-projects"></a> Creating</h3>
                <p>Creating a project is easy. You can access all projects from the main home page or from the drop down sub-navigation menu from anywhere within the application. From here you can click <span class="label label-success"><i class="fa fa-plus"></i> New</span> and a modal dialogue will pop up. Once you have completed the project creation form, the page will refresh and your project will be listed in the list of current projects.</p>
                <h3><a id="editing-projects"></a> Editing</h3>
                <p>You can edit a project by navigating that project's page and clicking on the <span class="label label-warning"><i class="fa fa-edit"></i> Edit</span> button. A modal form will pop up which will allow you to edit just about everything to do with the project. Things change and Entity {CC} lets you and your team adapt on the fly. Only project leads and users whose role allows them to can edit a project's details.</p>
                <h3><a id="deleting-projects"></a> Deleting</h3>
                <p>It is strongly advised not to delete projects. Instead, users can set their viewing preference to hide completed projects. By deleting a project in Entity, all associated data, tasks, notes, and forum info will be deleted along with it. This will create gaps in your ability to track important information in reports. However, if you really want to get rid of a project for good, you can delete itfrom the project page itself or from the all projects page. Either way you go, you will be asked to confirm your action. Once a project is deleted, it cannot be undone!</p>
                <h3><a id="project-access"></a> Access</h3>
                <p>Depending on a user's access, default access to a project is based on whether or not that user is assigned to the project or is a member of your staff. You can of course override the project access settings when you create new user roles. It is always best to think about who you want to have access to projects ahead of time so you don't have any sensitive information getting out among clients or subcontractors.</p>


                <!-- Files -->
                <h2 class="setting-group">Files</h2>
                <h3><a id="creating-files"></a> Creating</h3>
                <p>Up can create files by uploading them from the <span class="label label-info"><a href="<?= BASE_URL;?>uploads/" title="Personal files"><i class="fa fa-link"></i> personal user files page</a></span>. Navigate to the page and click new. You will notice that there are some restrictions on the types of files allowed to be uploaded. You can change these by editing the file.php class file in the app/classes/ folder. I recommend not allowing PHP and JS files, as they can be easily manipulated into opening up your site to attack. If you need to work with any of the listed files, you could always upload them in a ZIP package.</p>
                <p>Depending on which way you are accessing the file storage functionality, Entity will validate the selected file based on a few parameters. For example, if you are uploading an avatar, the file must be an image. All files that are uploaded using the system are limited ot the max upload size allowed by your <code>php.ini</code> file.</p>
                <p class="alert alert-info">When naming your actual files before uploading: DO NOT include spaces in the name of the file. Instead, use an underscore or hyphen to separate words. Also, do not use any special characters in the name of your file i.e. " ' ` ~  @ # $ % ^ & * ( ) etc. While this may or may not cause an error, initial testing has shown that it likely will.</p>
                <h3><a id="deleting-files"></a> Deleting</h3>
                <p>Deleting files is as easy as clicking on the <i class="fa fa-times-circle"></i> icon that pops up on hover. Once you delete the file, it will be removed from both the database and your server. This will remove that file from any tasks or other assigned places permanently!</p>
                <h3><a id="file-storage"></a> Storage</h3>
                <p>Files are stored in a generated folder in your files directory in the root of Entity. Further, files are placed in folders named by the id of the user that created them. <strong>Do not</strong> delete files manually without ensuring all references to them are gone from the database. Doing so will cause all kind of unexpected things to occur when the system tries to locate a missing file. Administrators can delete any file from within the application which automatically removes the file itself and any record of it in the database. User's can only delete files that they upload or as allowed by their access role.</p>
                <p class="alert alert-info">I am still working on the administrator's file access page. I am planning on revamping a lot of how Entity {CC} currently handles files.</p>
                <p>Avatar, project, and group images are stored in the main <code>img</code> directory and are renamed to the name of the object they represent. For example if you have a group called: <code>Test Group</code> and upload a group image called: <code>My Group Image.png</code>, then the image will be renamed to <code>test-group.png</code> and placed into the <code>img/groups/</code> directory.</p>
                <p><del>Users who have admin file rights will be able to see the "Uploads" section on the home page. This link will show all the files currently being tracked by the application and users who have access to this screen can delete files. Keep that in mind when assigning admin file rights when making a new role.</del></p>

                <!-- Invoices -->
                <h2 class="setting-group">Invoices</h2>
                <h3><a id="creating-invoices"></a> Creating</h3>
                <p>You can create a new invoice from the dashboard, inside a project, or on the view all invoices pages. Invoices can be tied to a specific project or they can be unassociated general charges that you require from a client. Users will need permission to create invoices and who and what they can charge are limited by both their permissions and the wages you set up. You will need a wage setup before creating an invoice. Entity comes with one already preconfigured for you, which is nothing more than one unit of your application's default currency. So if your currency is in USD, you will have a general wage set at $1 USD, allowing you to quickly create charges.</p>
                <h3><a id="editing-invoices"></a> Editing</h3>
                <p>Editing an invoice can be done from within that invoice. You can edit the general invoice information and any of the charges listed on it.</p>
                <h3><a id="deleting-invoices"></a> Deleting</h3>
                <p>Deleting invoices can only be done for invoices which are not yet paid. Once an invoice is paid, you can no longer delete it from the system. You can delete an open invoice by navigating to that invoice's page and clicking the delete button in the actions bar.</p>
                <h3><a id="invoice-access"></a> Access</h3>
                <p>Only users who can charge other and collect payments will have access to invoicing. These permissions are based on the access role assigned to that user's account.</p>
                <h3><a id="invoice-payment"></a> Payment</h3>
                <p>Entity currently offers one payment method: Stripe single checkout. It is fully integrated into the application and an invoice will automatically be changed to paid if the payment gateway replies with a confirmation of payment.</p>

                <!-- Wages and Taxes -->
                <h2 class="setting-group">Wages &amp; Taxes</h2>
                <h3><a id="about-wages"></a> About Wages</h3>
                <p>Wages allow you to have complete control over what kind of charges your employees and contractors can receive. If your business charges $25.00 per hour for PHP development, then you can create a hourly wage named PHP development and employees or contractors can use this when they charge for tracked time on a project. If there are no wages set, users will not be able to create an invoice charge.</p>
                <div class="well well-lg">
                    <dl>
                        <dt>Hourly Wages</dt>
                        <dd>Hourly wages can be used and assigned from the timer or entered manually on the invoice page.</dd>
                        <dt>Flat Wages</dt>
                        <dd>Flat wages are for flat rate fees you charge for certain services.</dd>
                    </dl>
                </div>
                <h3><a id="creating-wages"></a> Creating Wages</h3>
                <p>You can create a wage from the <a href="<?= BASE_URL;?>rates/" class="label label-primary" title="Wages &amp; Taxes"><i class="fa fa-link"></i> Wages &amp; Taxes Page</a>. Choose which kind of rate you want to create: an hourly or flat rate, and click <span class="label label-success"><i class="fa fa-plus"></i> New</span> button in the dark header bar.</p>
                <h3><a id="editing-wages"></a> Editing Wages</h3>
                <p>You can freely edit wages after they have been created by clicking on the  <span class="label label-warning"><i class="fa fa-edit"></i></span> button. All invoice line items that have already been created previous to the change will remain unaffected. This kind of information helps you out when looking over your financial timeline reports and making determinations about wage shifts.</p>
                <h3><a id="deleting-wages"></a> Deleting Wages</h3>
                <p>You can delete a wage by clicking the red <span class="label label-danger"><i class="fa fa-times-circle"></i></span> button next to the wage in question. Deleting a wage will not alter invoices that currently use that wage in their calculations.</p>
                <h3><a id="about-taxes"></a> About Taxes</h3>
                <p>Taxes have been a long requested addition to Entity. In 2.0.x you can create taxes and assign them independently to invoices and charges. Make sure to assign taxes before publishing the invoice. The only catch with taxes is, that if you edit or delete a tax, it will be removed from the system entirely and that includes any invoices past or present, that have that tax added to them. I currently working on a way to improve this.</p>
                <h3><a id="creating-taxes"></a> Creating Taxes</h3>
                <p>You can create a tax from the <a href="<?= BASE_URL;?>rates/" title="Wages &amp; Taxes">Wages &amp; Taxes Page</a>. Click the <span class="label label-success"><i class="fa fa-plus"></i> New</span> button in the dark header bar.</p>
                <h3><a id="editing-taxes"></a> Editing Taxes</h3>
                <p>You can freely edit taxes after they have been created by clicking on the  <span class="label label-warning"><i class="fa fa-edit"></i></span> button. All invoice line items that have already been created previous to the change, that are not paid yet will be updated.</p>
                <h3><a id="deleting-taxes"></a> Deleting Taxes</h3>
                <p>You can delete a tax by clicking the red <span class="label label-danger"><i class="fa fa-times-circle"></i></span> button next to the tax in question. Deleting a tax will remove it from any unpaid invoice calculations that use that tax!</p>
                <h3><a id="applying-wages-taxes"></a> Applying Wages and Taxes</h3>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>

                <h2 class="setting-group">Payment Collection</h2>
                <h3><a id="company-stripe"></a> Company Stripe Account</h3>
                <p>You can set a single Stripe account for your organization from within the application's settings page. This account will collect all funds from invoices designated as paid to company vs. an individual. This is perfect if you are the only person in a small business or you don't want employees collecting funds directly. You will need a Stripe account to get your API keys. Once you have those keys, you can then enter them into your application's database from the settings page. They will be securely stored.</p>
                <h3><a id="user-stripe"></a> User Stripe Accounts</h3>
                <p>Each user can setup their own Stripe account information within their account profile. They can use this to collect payments from invoices they create.</p>
                <div class="well well-lg">
                    <p class="alert alert-info">Paypal will be implemented in version 2.0.2. This documentation is placeholder information.</p>
                    <h3><a id="company-paypal"></a> Company PayPal Account</h3>
                    <p>As with the Company Stripe account, if this is set to on, your clients and customers will have the option to pay using PayPal. Your PayPal account email is stored in the database.</p>
                    <h3><a id="user-paypal"></a> User PayPal Accounts</h3>
                    <p>Additionally, each user can provide their own PayPal emails to allow clients the ability to pay them using PayPal.</p>
                </div>


                <!-- The Calendar -->
                <h2 class="setting-group">The Calendar</h2>
                <p class="alert alert-info">
                    The calendar will be implemented fully in version 2.0.3
                </p>
                <h3><a id="calendar-usage"></a> Usage</h3>
                <p>New in Entity {CC} 2.0.x is the calendar. Now you can track all important checkpoints from one place. The calendar has filters which allow you to view only those things which you want to view. The default filters are:</p>
                <ul class="list-unstyled">
                    <li><i class="fa fa-check-circle"></i> Project Checkpoints</li>
                    <li><i class="fa fa-check-circle"></i> Project Stages</li>
                    <li><i class="fa fa-check-circle"></i> Tasks (Staged and Project General)</li>
                    <li><i class="fa fa-check-circle"></i> Invoices</li>
                </ul>
                <p class="alert alert-info">Each of the items on the calendar are color coded. Blue is a general color for information, Yellow is something which needs attention i.e. an invoice 1 week of being due, and Red shows alerts that you need to be wary of.</p>
                <h4><a id="project-checkpoints"></a> Project Checkpoints</h4>
                <p>Selecting this filter will show the start and end dates for projects. Clicking on the link will take the user to the project page.</p>
                <h4><a id="project-stages"></a> Project Stages</h4>
                <p>Selecting this filter will show all assigned project stage due dates (if applicable).</p>
                <h4><a id="project-tasks"></a> Tasks</h4>
                <p>Depending on the user's calendar access, users can see all current tasks tracked by the system to only those tasks they are assigned to. Clicking on any task will link the user to the project page of that particular task.</p>

                <!-- Application Settings -->
                <h2 class="setting-group">Application Settings</h2>
                <h3><a id="company-information"></a> Company Information</h3>
                <p>Provide the most information as possible for your company to personalize the look and feel of the interface for staff and clients. Phone numbers and email information will be visible on the contractor and client dashboards so make sure they are correct. If no information is provided it will not be shown.</p>
                <h3><a id="login-configuration"></a> Login Configuration</h3>
                <p>Here you can configure the requirements for logging in to your application. You can toggle the requirement for a user's email in addition to their username and password for increased security. You can also toggle the ability for users to register for an account based on your own business practice. If you want to be responsible for creating each and every user account you can turn off the ability for anonymous users to register for an account. You can also determine what role users who register for an account will be assigned by default.</p>
                <p>Anonymous quotes can be toggled on an off. If it is set to on a button will appear on the main login page. You will have ot have a custom form created and configured to show up if you decide to turn this feature on.</p>
                <h3><a id="email-notifications"></a> Email Notifications</h3>
                <p>Entity can be set to send an email to the application owner when a user registers for an account and when someone submits a custom quote form.</p>
                <h3><a id="downtime-settings"></a> Downtime &amp; Maintenance</h3>
                <p>Maintenance mode will keep users from logging in and using the application while you perform custom development or some other chore on the backend. You can access the system while it is in maintenance mode by setting an access key. You can apply this key by appending <code>?key=mysecretkey</code> onto the url at the login screen. You will need to replace: 'mysecretkey' with your own key. If the key is correct, a cookie will be stored in your browser allowing you to access the system. Make sure you keep this key a secret and change it often.</p>
                <p>Google analytics can be turned on and off. You will need to provide your Google analytics tracking code as well.</p>

                <!--  Activity Log -->
                <h2 class="setting-group"><a id="activity-log"></a>The Activity Log</h2>
                <h3><a id="cleaning-log"></a>Cleaning the Log</h3>
                <p>Every once in a while you will feel the need to slim down your activity log feed. In fact Entity logs so many actions that it can easily reach over 4 to 5 pages in a matter of days on a busy portal. If you have already thoroughly reviewed your log and would like to trim down the results, you can always clean up the log by clicking on the <span class="label label-primary"><i class="fa fa-paint-brush"></i> Clean Log</span> button in the action bar. Doing this will not really "delete" the log entries, rather it will set them to "not visible" in your database. Doing this is much preferable to erasing the log entirely because Entity uses a lot of your log entries for statistical reporting.</p>
                <h3><a id="erasing-log"></a>Erasing the Log</h3>
                <p>If for some reason you feel the need to empty out your activity log, be sure to back it up first to one of the two file types offered (Text and CSV / Excel). You can also print a copy of the log for your own physical records if you like. Once you are completely sure you are ready you want to dump the data, you can click on the <span class="label label-primary"><i class="fa fa-eraser"></i> Erase Log</span> button in the action bar. You will be asked to confirm your action and it will be done. You will NOT be able to get the data back after it has been removed from your server.</p>
                <!--  Translation -->
                <h2 class="setting-group">Translation</h2>
                <h3><a id="adding-translation"></a> Adding a Translation File <sup>Not implemented yet</sup></h3>
                <p>Translation files are located in the <code>app/languages/</code> folder and follow a specific naming convention. Users have access to change the language of the application in the dashboard. Entity will automatically detect and add translations on boot. The easiest way to correctly create a translation file is to copy one of the supplied translation files i.e. the <code>english.language.php</code> or <code>spanish.language.php</code> file and make changes. Which one you use as a starter for your translation is based on your familiarity with the provided languages.</p>
                <p class="alert alert-warning">Keep in mind that the language translation capabilities will only apply to system messages and text. All user supplied content will not be translated.</p>

                <!-- Successful usage -->
                <h2 class="setting-group">Successful usage</h2>
                <p>Even though this application is very flexible ,it does have an opinion on how project management should be done. Most of the time you can use it's tools in numerous ways that I didn't intend them for, but there are times when functionality is the way it is because of a specific reason. Due to this, I do not support customizations or provide features for individual users. This application gets new features and fixes from feedback provided on CodeCanyon and my <a href="http://www.zenperfectdesign.com" title="Zen Perfect Design Homepage" target="_blank">website</a>.</p>
                <p>This application is an amazing piece of code but it is marketed and designed for the masses. Submitted feature requests are weighed on how they will benefit the program and the multitude of customers using it as whole.</p>
                <h3><a id="customization"></a>Customization</h3>
                <p>You can edit any part or portion of the code to suit your needs. However, I do not support user customization and app folder upgrades will often overwrite your changes. Any customization you make is done at your own peril. I highly recommend if you do make customizations, you take notes and have a working knowledge of HTML, CSS, and most importantly, PHP. I release a detailed changelog that details what files have been changed with each release. If you have edited any of those files, make sure you know how to make your customizations work with the new features.</p>
                <h3><a id="semantic-versioning"></a>Semantic Versioning</h3>
                <p>This application uses semantic versioning. Semantic versioning is represented by a number such as 2.0.0. When you break this number down the 2 is a major upgrade that will not be reverse compatible with older versions such as 1.5.x. The second 0 is the minor version. This will change as large, new features are added. Finally, the last number is the patch version. This version is usually to fix security holes or bugs. No new features are added to a patch release, but I might make some display changes which may look like new features, but are intended to improve access and action.</p>
                <h3><a id="support"></a>Support</h3>
                <p>I only provide support for Entity {CC} out of the box features. Bugs will be fixed ASAP. Support is offered through CodeCanyon's support interface.</p>
                <h3><a id="no-nos"></a>No nos</h3>
                <p>Never manually delete anything from the database! Doing so can lead to very unexpected results which may not be fixable.</p>
            </div>
        </div>
    </div>
</div>

<div class="section-support">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Want me to improve this documentation?</h1>
                <p>Share your thoughts: support@zenperfectdesign.com</p>
            </div>
        </div>
    </div>
</div>