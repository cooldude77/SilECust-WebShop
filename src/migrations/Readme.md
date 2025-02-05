use this setting on your project in doctrine_migrations.yaml
```
doctrine_migrations:
    migrations_paths:
    # namespace is arbitrary but should be different from App\Migrations
    # as migrations classes should NOT be autoloaded
        'App\migrations': '<path to app>/vendor/silecust/silecust/src/migrations'
    enable_profiler: false```