# Syncora - Video Conferencing SaaS


Syncora is a sleek, modern, and user-friendly video conferencing platform powered by Laravel 11 and integrated with the Jitsi API. Whether you're hosting an instant meeting or scheduling one for later, Syncora makes connecting with others seamless and efficient. With built-in role management and email invitations with ICS files, it's a great solution for agents and users alike.

## Features

- **Instant & Scheduled Meetings**: Effortlessly create instant or scheduled meetings.
- **Email Invitations**: Attendees receive email invitations with an ICS file containing all necessary meeting details.
- **Role Management**: Two user types:
  - **Agents**: Can create meetings, send invites, and manage recordings (coming soon!).
  - **Users**: Join meetings with just a click via emailed links.
- **Meeting Details**: Includes title, start/end dates/times, time zone, location, and attendee info.
- **Jitsi Integration**: Enjoy high-quality video conferencing powered by Jitsi Meet.

## What’s Next? 🚀

- **Recording**: Soon, agents will be able to record meetings.
- **Expanded User Roles**: We're working on more advanced user roles to customize access levels.
- **Mobile App**: Syncora on the go — stay tuned!

## Why Syncora? 🤔

- **User-Friendly**: We designed Syncora to be easy for anyone to use.
- **Built for Efficiency**: No need to deal with complicated setups — just send an invite, and you're good to go.
- **Secure & Reliable**: Powered by Jitsi and Laravel, we ensure that your meetings are safe and reliable.

## Get Involved! 🌟

Syncora is a work in progress, and we're always looking to improve. If you’ve got ideas or feedback, hit us up! We're excited to make this platform the best it can be.

## Contact

Have questions, or want to chat about Syncora?

- Email: zanlwine.htoo110@gmail.com
- Website: [syncora.com](http://syncora.com) (Coming soon)

---

**Built with ❤️ by Zan**

---

<!-- Animated Sync Loader (Optional) -->
<div class="sync-loader">
   <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
       <circle cx="50" cy="50" r="45" stroke="blue" stroke-width="4" fill="none" />
       <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="none" />
   </svg>
</div>

---

### **CSS for Sync Loader Animation:**

```css
@keyframes syncAnimation {
    0% { transform: rotate(0deg); }
    50% { transform: rotate(180deg); }
    100% { transform: rotate(360deg); }
}

.sync-loader {
    animation: syncAnimation 2s infinite;
}
