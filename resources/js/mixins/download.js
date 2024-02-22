import { ref } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const currentOrderID = ref(null);

    const download = (data, type) => {
      let blob = new Blob([data], { type: 'application/' + type });
      let link = document.createElement('a');
      link.href = window.URL.createObjectURL(blob);
      link.download = currentOrderID.value + '.' + type;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    };

    const downloadURI = (uri, name) => {
      var link = document.createElement("a");
      link.download = name;
      link.target = "_blank";
      link.href = uri;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    };

    const downloadURINew = async (url, name) => {
      console.log('download uri');
      let blob = await fetch(url).then((r) => r.blob());
      var f = new FileReader();
      f.readAsDataURL(blob);
      f.onload = d => {
        var uri = d.target.result;
        var link = document.createElement('a');
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        //delete link;
      };
    };

    const downloadFileViaURL = (url, name) => {
      console.log('donwload file');
      axios({
        url: url,
        method: 'GET',
        responseType: 'blob', // important
      }).then((response) => {
        console.log('response download');
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', name);
        document.body.appendChild(link);
        link.click();
      });
    };

    return {
      currentOrderID,
      download,
      downloadURI,
      downloadURINew,
      downloadFileViaURL
    };
  }
};
