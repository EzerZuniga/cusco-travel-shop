<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Clase Kernel de consola
 *
 * Aquí se registran los comandos Artisan personalizados y la
 * programación (scheduling) de tareas periódicas.
 *
 * Comentarios y ejemplos están en español para facilitar mantenimiento.
 */
class Kernel extends ConsoleKernel
{
    /**
     * Comandos Artisan proporcionados por la aplicación.
     *
     * Añade aquí cualquier comando personalizado ubicado en
     * `app/Console/Commands` si deseas registrarlos manualmente.
     * En la mayoría de los casos los comandos se cargan automáticamente
     * usando el método `commands()` más abajo.
     *
     * @var array
     */
    protected $commands = [
        // Ejemplo: App\\Console\\Commands\\MiComando::class,
    ];

    /**
     * Definir la programación de comandos de la aplicación.
     *
     * Aquí puedes programar tareas periódicas con el scheduler de Laravel.
     * Mantén las tareas idempotentes y evita procesos de larga duración
     * dentro de `schedule()` (usa jobs/queue para trabajos pesados).
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Ejemplo: mostrar la frase inspiradora cada hora (comando builtin de Laravel)
        // $schedule->command('inspire')->hourly();

        // Ejemplo: reiniciar workers de cola diariamente para recoger nuevos despliegues
        // $schedule->command('queue:restart')->daily();

        // Ejemplo: una tarea personalizada definida como comando Artisan
        // $schedule->command('tours:sync')->dailyAt('02:00')->withoutOverlapping();

        // Buenas prácticas: usar ->withoutOverlapping() para evitar ejecuciones simultáneas
        // cuando la tarea podría tardar más de su intervalo.
    }

    /**
     * Registrar los comandos para la aplicación.
     *
     * Este método carga automáticamente los comandos localizados en
     * `app/Console/Commands` y requiere el archivo `routes/console.php`
     * donde se pueden definir closures para comandos Artisan.
     *
     * @return void
     */
    protected function commands()
    {
        // Cargar automáticamente los comandos PHP dentro de app/Console/Commands
        $this->load(__DIR__ . '/Commands');

        // Incluir definiciones de comandos basadas en closures (opcional)
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}
