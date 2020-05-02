function ajax() {

    this.connect = function() {
        var http = null;
        try {
            http = new XMLHttpRequest(); //ab IE 7
        } catch (e) {
            try {
                http = new ActiveXObject("Microsoft.XMLHTTP"); //ab IE6
            } catch (e) {
                try {
                    http = new ActiveXObject("Msxml2.XMLHTTP"); //ab IE5 
                } catch (e) {
                    http = null;
                }
            }
        }
        return http;
    };

}