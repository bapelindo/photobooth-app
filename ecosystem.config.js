module.exports = {
    apps: [
        {
            name: 'photobooth-email-worker',
            script: 'scripts/email_worker.php',
            interpreter: 'php',
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '100M',
            env: {
                NODE_ENV: 'production'
            }
        },
        {
            name: 'photobooth-print-worker',
            script: 'scripts/print_worker.php',
            interpreter: 'php',
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '100M',
            env: {
                NODE_ENV: 'production'
            }
        }
    ]
};
