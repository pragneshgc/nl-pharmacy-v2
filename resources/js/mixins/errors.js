const reportError = (error) => {
    let audio = new Audio('/audio/alarm.mp3');
    audio.play();

    this.$toasted.show('Error fetching data!', 
        {
            type : 'error',
            iconPack: 'fontawesome',
            icon : 'exclamation',                    
            duration : 5000,
            position: "top-right",
            action : {
                text : 'Dismiss',
                onClick : (e, toastObject) => {
                    toastObject.goAway(0);
                }
            },
        }
    )
};

const postError = (error) => {
    let audio = new Audio('/audio/alarm.mp3');
    audio.play();

    this.$toasted.show(error, 
        {
            type : 'error',
            iconPack: 'fontawesome',
            icon : 'exclamation',
            duration : 5000,
            position: "top-right",
            action : {
                text : 'Dismiss',
                onClick : (e, toastObject) => {
                    toastObject.goAway(0);
                }
            },
        }
    )
};


const postErrorPopup = (error) => {
  let audio = new Audio('/audio/alarm.mp3');
  audio.play();

  Swal.fire({
    icon: 'error',
    title: 'Whoops!',
    showConfirmButton: false,
    html: error,
    showConfirmButton: true,
  }).then((result) => {
    console.warn(result);
  });
};

const postSuccess = (response) => {
  Swal.fire({
    position: 'bottom',
    icon: 'success',
    title: 'Success!',
    showConfirmButton: false,
    timer: 5000,
    //timer: 9999999999999999,
    toast: true,
    text: response,
  });
};

const postWarning = (response) => {
    if(response == ''){
        response = 'Success!'
    }  

    this.$toasted.show(response,
        {
            iconPack: 'fontawesome',
            type : 'warning',
            icon : 'exclamation',
            duration : 2000,
            position: "top-right",
            action : {
                text : 'Dismiss',
                onClick : (e, toastObject) => {
                    toastObject.goAway(0);
                }
            },
        }
    )    
}


export default {
  reportError,
  postError,
  postErrorPopup,
  postSuccess,
  postWarning  
}
