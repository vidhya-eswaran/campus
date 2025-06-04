use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

if (!function_exists('customFileStorage')) {
    /**
     * Store a file with a custom path and generate a full URL for download.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $directory
     * @return string  The full URL to the stored file.
     */
    function customFileStorage($file, $directory = 'announcements')
    {
        // Get the original file name and generate a new unique name using current time
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Define the destination path (ensure this directory exists in your server)
        $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/' . $directory . '/';

        // Move the file to the destination path
        $file->move($destinationPath, $fileName);

        // Generate the full URL to access the file
        $baseUrl = env('APP_URL');  // Or specify your base URL manually
        $fullFileName = $baseUrl . '/' . $directory . '/' . $fileName;

        return $fullFileName;
    }
}
