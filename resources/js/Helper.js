export class Helper {
  static getBaseUrl() {
    return window.location.origin;
  }

  static getApiUrl() {
    return `${this.getBaseUrl()}/api`;
  }

  static getResourceUrl(resource) {
    return `${this.getApiUrl()}/${resource}`;
  }

  static getImageUrl(imagePath) {
    return `${this.getBaseUrl()}/images/${imagePath}`;
    }
    static getStatusLabel(status) {
        switch (status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Approved';
            case 2:
                return 'Rejected';
            case 3:
                return 'Draft';
            case 4:
                return 'Closed';
            default:
                return 'Unknown';
        }
    }
    static paymentStatusLabel(status) {
        const status = { 0: due, 1: paid, 2: refunded }
        return status[status] || 'Unknown';
    }

}