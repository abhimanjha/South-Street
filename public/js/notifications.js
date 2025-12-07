// Flipkart-Style Notification System
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
        this.container.style.cssText = `
            position: fixed;
            top: 80px;
            right: -400px;
            width: 350px;
            z-index: 9999;
            pointer-events: none;
        `;
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
        notifElement.className = 'flipkart-notification';
        notifElement.style.cssText = `
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
            right: -400px;
            pointer-events: auto;
            border-left: 4px solid #2874f0;
            animation: slideInRight 0.5s ease-out forwards;
        `;

        const icon = this.getIcon(notification.type);
        const color = this.getColor(notification.type);

        notifElement.innerHTML = `
            <div style="display: flex; align-items: start; gap: 12px;">
                <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 50%; background: ${color}; display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fas ${icon}"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h6 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #212121;">
                        ${notification.title || 'Notification'}
                    </h6>
                    <p style="margin: 0; font-size: 13px; color: #878787; line-height: 1.4;">
                        ${notification.message}
                    </p>
                    ${notification.discount_code ? `
                        <div style="margin-top: 8px; padding: 6px 10px; background: #fff4e5; border-radius: 4px; display: inline-block;">
                            <span style="font-size: 12px; color: #ff9f00; font-weight: 600;">
                                Code: ${notification.discount_code} - ${notification.discount_percentage}% OFF
                            </span>
                        </div>
                    ` : ''}
                </div>
                <button onclick="this.closest('.flipkart-notification').remove()" style="background: none; border: none; color: #878787; cursor: pointer; padding: 0; font-size: 18px; line-height: 1;">
                    ×
                </button>
            </div>
        `;

        this.container.appendChild(notifElement);

        // Auto remove after 10 seconds with fade-out animation
        setTimeout(() => {
            notifElement.style.animation = 'slideOutLeft 0.5s ease-in forwards';
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

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            right: -400px;
            opacity: 0;
        }
        to {
            right: 0;
            opacity: 1;
        }
    }

    @keyframes slideOutLeft {
        from {
            right: 0;
            opacity: 1;
        }
        to {
            right: -400px;
            opacity: 0;
        }
    }

    .flipkart-notification:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    @media (max-width: 768px) {
        #notification-container {
            width: calc(100% - 20px);
            right: -100%;
            left: 10px;
        }

        @keyframes slideInRight {
            from {
                right: -100%;
                opacity: 0;
            }
            to {
                right: 0;
                opacity: 1;
            }
        }

        @keyframes slideOutLeft {
            from {
                right: 0;
                opacity: 1;
            }
            to {
                right: -100%;
                opacity: 0;
            }
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
