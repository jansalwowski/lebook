export default {

    data(){
        return {
            alerts: {
                success: {
                    show: false,
                    message: 'Success!'
                },

                danger: {
                    show: false,
                    message: 'Error!'
                },

                info: {
                    show: false,
                    message: 'Done!'
                },

                warning: {
                    show: false,
                    message: 'Warning!'
                }

            }
        }
    },

    methods: {

        showSuccessAlert(message) {
            if (message) {
                this.alerts.success.message = message;
            }

            this.alerts.success.show = true;
        },

        showDangerAlert(message) {
            if (message) {
                this.alerts.danger.message = message;
            }

            this.alerts.danger.show = true;
        },

        showInfoAlert(message) {
            if (message) {
                this.alerts.info.message = message;
            }

            this.alerts.info.show = true;
        },

        showWarningAlert(message) {
            if (message) {
                this.alerts.warning.message = message;
            }

            this.alerts.warning.show = true;
        }
    },

    events: {
        alertSuccess(message){
            this.showSuccessAlert(message);
        },

        alertDanger(message){
            this.showDangerAlert(message);
        },

        alertInfo(message){
            this.showInfoAlert(message);
        },

        alertWarning(message){
            this.showWarningAlert(message);
        }
    }
}