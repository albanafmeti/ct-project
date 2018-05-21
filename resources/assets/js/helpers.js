export default {
    handleError: function (response) {
        if (response.status === 403) {
            if (response.data.success === false) {
                toastr.error(response.data.message);
            }
        } else if (response.status === 422) {
            let errors = response.data.errors;

            for (let name in errors) {
                toastr.error(errors[name][0]);
                break;
            }
        } else if (response.status === 405) {

            let error = response.data.exception;
            toastr.error('Server Error. Please contact support center.');
        } else if (response.status === 500) {

            let error = response.data.exception;
            toastr.error('Server Error. Please contact support center.');
        }
    }
}
