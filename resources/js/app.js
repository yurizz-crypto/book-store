import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    
    if (userIdMeta && window.Echo) {
        const userId = userIdMeta.getAttribute('content');
        
        if (userId) {
            window.Echo.private(`user.${userId}`)
                .listen('ExportReady', (e) => {
                    console.log('Export ready! Automatically downloading:', e.downloadUrl);
                    
                    // Create an invisible link to force the browser to download
                    const link = document.createElement('a');
                    link.href = e.downloadUrl;
                    link.setAttribute('download', ''); // Triggers download prompt
                    link.setAttribute('target', '_blank'); // Fallback if direct download is blocked
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
        }
    }
});