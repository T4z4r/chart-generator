# Chart Generator

A Laravel-based web application for generating, managing, and customizing charts. This application allows users to create, edit, view, and customize various types of charts with ease.

## Features

- **Create Charts**: Easily generate new charts with customizable data.
- **Edit Charts**: Modify existing charts and update their properties.
- **View Charts**: Display charts in a user-friendly interface.
- **Customization**: Add custom fields and styling options for charts.
- **Responsive Design**: Built with Laravel and Blade templates for a seamless experience.

## Installation

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd chart-generator
   ```

2. **Install Dependencies**:
   - Ensure you have PHP (8.1 or higher) and Composer installed.
   - Run:
     ```bash
     composer install
     ```

3. **Set Up Environment**:
   - Copy `.env.example` to `.env` and configure your database settings.
   - Generate an application key:
     ```bash
     php artisan key:generate
     ```

4. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Install Node Dependencies** (for assets):
   ```bash
   npm install
   npm run build
   ```

6. **Start the Application**:
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

## Usage

1. **Access the Application**: Navigate to the homepage to view existing charts.
2. **Create a Chart**: Use the interface to input data and generate a new chart.
3. **Edit a Chart**: Select a chart from the list and modify its details.
4. **View Charts**: Click on a chart to see its full display.
5. **Customize**: Use the edit options to add custom fields or styling.

## File Structure

```
chart-generator/
├── app/
│   ├── Http/Controllers/
│   │   └── ChartController.php          # Handles chart-related requests
│   ├── Models/
│   │   └── Chart.php                    # Chart model for database interactions
├── database/
│   ├── migrations/
│   │   ├── 2025_10_24_115549_create_charts_table.php
│   │   └── 2025_10_24_115550_add_customization_fields_to_charts_table.php
├── resources/
│   ├── css/
│   │   └── app.css                      # Application styles
│   ├── js/
│   │   └── app.js                       # JavaScript assets
│   ├── views/
│   │   ├── charts/
│   │   │   ├── index.blade.php          # List of charts
│   │   │   ├── edit.blade.php           # Edit chart form
│   │   │   └── show.blade.php           # Display individual chart
│   │   └── layouts/
│   │       └── app.blade.php            # Main layout template
├── routes/
│   └── web.php                          # Web routes
├── public/                              # Public assets
├── storage/                             # Storage directories
├── tests/                               # Unit and feature tests
└── README.md                            # This file
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
