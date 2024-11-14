<?php

if (!function_exists('log_action')) {
    /**
     * A helper function to log user actions.
     *
     * @param string $log_level The level of the log (e.g., 'info', 'success', 'warning', 'error')
     * @param string $description A brief description of the action performed
     * @return \App\Models\UserLog
     */
    function log_action($log_level, $description)
    {
        // Define valid log levels
        $validLogLevels = ['info', 'success', 'warning', 'error'];

        // Check if the provided log level is valid
        if (!in_array($log_level, $validLogLevels)) {
            throw new \InvalidArgumentException("Invalid log level: {$log_level}. Valid values are 'info', 'success', 'warning', 'error'.");
        }

        // If valid, create the log entry
        return \App\Models\UserLog::create([
            'user_id' => auth()->id(),  // Automatically get the authenticated user ID
            'log_level' => $log_level,  // The log level, e.g., 'info', 'success', 'warning', 'error'
            'description' => $description,  // The description of the action performed
        ]);
    }
}
