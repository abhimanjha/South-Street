// Modern Notification System
class NotificationManager {
    constructor() {
        this.container = null;
        this.init();
        this.checkForNotifications();
    }

    init() {
        // Create notification container
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        document.body.appendChild(this.container);
    }

    checkForNotifications() {
        // Check immediately on load
        this.fetchNotifications();
        // Then check for new notifications every 30 seconds
        setInterval(() => this.fetchNotifications(), 30000);
    }

    async fetchNotifications() {
        try {
            console.log('Fetching notifications...');
            const response = await fetch('/api/notifications/unread');
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Notifications data:', data);
                
                if (data.notifications && data.notifications.length > 0) {
                    console.log('Found', data.notifications.length, 'notifications');
                    data.notifications.forEach((notification, index) => {
                        setTimeout(() => {
                            this.show(notification);
                        }, index * 2000); // Stagger notifications
                    });
                } else {
                    console.log('No unread notifications');
                }
            } else {
                console.error('Failed to fetch notifications, status:', response.status);
            }
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        }
    }

    show(notification) {
        const notifElement = document.createElement('div');
        notifElement.className = 'notification-card';

        const icon = this.getIcon(notification.type);
        const color = this.getColor(notification.type);

        notifElement.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon" style="background:${color}">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="notification-body">
                    <h6 class="notification-title">${notification.title || 'Notification'}</h6>
                    <p class="notification-message">${notification.message || ''}</p>
                    ${notification.discount_code ? `
                        <div class="notification-code">
                            Code: ${notification.discount_code} - ${notification.discount_percentage}% OFF
                        </div>
                    ` : ''}
                </div>
                <button class="notification-close" onclick="this.closest('.notification-card').remove()">×</button>
            </div>
        `;

        this.container.appendChild(notifElement);

        // Auto remove after 10 seconds with fade-out animation
        setTimeout(() => {
            notifElement.classList.add('hide');
            setTimeout(() => {
                if (notifElement.parentNode) {
                    notifElement.remove();
                }
                // Mark as read
                if (notification.id) {
                    this.markAsRead(notification.id);
                }
            }, 500);
        }, 10000);
    }

    getIcon(type) {
        const icons = {
            'discount': 'fa-tag',
            'offer': 'fa-gift',
            'order': 'fa-shopping-bag',
            'info': 'fa-info-circle',
            'success': 'fa-check-circle',
            'warning': 'fa-exclamation-triangle',
            'error': 'fa-times-circle'
        };
        return icons[type] || 'fa-bell';
    }

    getColor(type) {
        const colors = {
            'discount': '#ff9f00',
            'offer': '#2874f0',
            'order': '#388e3c',
            'info': '#2196f3',
            'success': '#4caf50',
            'warning': '#ff9800',
            'error': '#f44336'
        };
        return colors[type] || '#2874f0';
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json'
                }
            });
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }
}

// Add styles
const style = document.createElement('style');
style.textContent = `
    #notification-container {
        position: fixed;
        top: 80px;
        right: 20px;
        width: 360px;
        z-index: 9999;
        pointer-events: none;
    }

    .notification-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        margin-bottom: 12px;
        pointer-events: auto;
        overflow: hidden;
        animation: notifSlideIn 0.4s ease-out forwards;
    }

    .notification-card.hide {
        animation: notifSlideOut 0.3s ease-in forwards;
    }

    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
    }

    .notification-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: inset 0 -8px 16px rgba(0,0,0,0.08);
    }

    .notification-title {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .notification-message {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    .notification-code {
        margin-top: 8px;
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        background: #fef3c7;
        color: #b45309;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #fde68a;
    }

    .notification-close {
        background: transparent;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        padding: 0;
        margin-left: 8px;
    }

    .notification-close:hover {
        color: #4b5563;
    }

    @keyframes notifSlideIn {
        from { transform: translateX(24px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes notifSlideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(-24px); opacity: 0; }
    }

    @media (max-width: 768px) {
        #notification-container {
            width: calc(100% - 24px);
            right: 12px;
            left: 12px;
        }
    }
`;
document.head.appendChild(style);

// Initialize notification manager when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.notificationManager = new NotificationManager();
    });
} else {
    window.notificationManager = new NotificationManager();
}
