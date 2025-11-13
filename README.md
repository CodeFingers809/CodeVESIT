# CSI-VESIT College Platform

A comprehensive college management platform built with Laravel 11, combining features from Reddit, Discord, and Notion to help students organize their academic lives.

## Features

### 1. Study Group Streams
- **Create and Join Study Groups**: Request to create study groups for your classes (admin-moderated)
- **Four Channels per Study Group**:
  - **Todos**: Moderators post course todos; members track completion
  - **Announcements**: Important updates and notifications
  - **Chat**: Group discussion with reporting capabilities
  - **Personal Calendar**: Individual task and event management

### 2. All-College Forums
- College-wide discussion forum
- Create posts and engage in discussions
- Comment on posts with threaded replies
- Report inappropriate content for admin review

### 3. Blog System
- Write and submit blog posts (Medium-style)
- Admin moderation before publication
- View published blogs from all users
- Track your blog submissions

### 4. Events Calendar
- View upcoming college events
- Request to create new events
- Event details include dates, location, and organizer info
- Admin moderation for event requests

### 5. Comprehensive Admin Panel
- Approve/reject study group requests
- Moderate blog submissions
- Review event requests
- Handle user reports
- User management (roles and status)
- Database overview

## Technology Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Breeze with email verification
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL
- **PHP Version**: 8.4+

## Installation

### Prerequisites
- PHP 8.4 or higher
- Composer
- MySQL
- Node.js and NPM

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd CodeVESIT
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file and set your MySQL credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=csi_vesit_platform
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Create database**
   ```bash
   mysql -u root -p
   CREATE DATABASE csi_vesit_platform;
   exit;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Visit the application**
    Open your browser and go to `http://localhost:8000`

## Email Verification

The platform requires users to register with @ves.ac.in email addresses only. To test email verification in development:

1. Configure mail settings in `.env`:
   ```env
   MAIL_MAILER=log
   ```
   This will log emails to `storage/logs/laravel.log`

2. Or use Mailtrap/Mailhog for testing:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   ```

## Creating an Admin User

After registering a regular user, you can promote them to admin via database:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your_email@ves.ac.in';
```

Or use Laravel Tinker:
```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'your_email@ves.ac.in')->first();
>>> $user->role = 'admin';
>>> $user->save();
```

## User Roles

- **User**: Default role with access to all student features
- **Moderator**: Can moderate study groups they're assigned to
- **Admin**: Full access to all moderation and management features

## Database Schema

The platform includes comprehensive database migrations for:
- Users (with profile details)
- Study Groups and Members
- Study Group Channels (Todos, Announcements, Messages)
- Personal Calendar Events
- Forums, Posts, and Comments
- Blogs and Blog Requests
- Events and Event Requests
- Reports (for content moderation)

## Security Features

- Email verification required (@ves.ac.in only)
- Role-based access control
- CSRF protection
- Password hashing
- Input validation and sanitization
- Report system for inappropriate content

## Responsive Design

The platform is fully responsive and works on:
- Desktop browsers
- Tablets
- Mobile devices

Built with Tailwind CSS for a modern, clean interface.

## Development

### Run tests
```bash
php artisan test
```

### Code style
```bash
./vendor/bin/pint
```

### Watch for asset changes
```bash
npm run dev
```

## Key Routes

- `/` - Homepage
- `/register` - User registration
- `/login` - User login
- `/dashboard` - Main dashboard
- `/study-groups` - Study groups listing
- `/forums` - Forums listing
- `/blogs` - Blog posts
- `/events` - Events calendar
- `/calendar` - Personal calendar
- `/admin` - Admin panel (admin only)

## Contributing

This platform is built for CSI-VESIT. For contributions or issues, please contact the CSI-VESIT council.

## License

This project is proprietary software developed for VESIT college.

## Contact

For support or queries, contact the CSI-VESIT Secretary.
