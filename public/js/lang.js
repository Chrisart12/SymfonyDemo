

document.addEventListener("DOMContentLoaded", () => {

    const changeLocale = (newLocale) => {
        // On recherche l'origin : http://localhost:8000
        let currentOrigin = window.location.origin
        // On recherche le pathname : /fr/admin/recipe/
        let currentPathname = window.location.pathname

        // On retire le locale du pathname
        let curentPathnameWithoutLocale = currentPathname.substring(3)

        // On remplace le locale par le nouveau locale
        let newLocation = `${currentOrigin}/${newLocale}${curentPathnameWithoutLocale}`

        return newLocation
    }
 
    // const country_lang_div = document.getElementById("country_lang")

    const  dynamicSelectOptions = document.querySelectorAll(".dynamic-select-option")
    const country_lang = document.querySelector("input[name='country_lang']");


    if (dynamicSelectOptions) {
        dynamicSelectOptions.forEach(dynamicSelectOption => {
            dynamicSelectOption.addEventListener('click', function() {

                if (country_lang.value) {

                    let newURL = changeLocale(country_lang.value)
                    // history.pushState({}, null, newURL);

                    window.location.replace(
                        newURL
                    );

                    return

            
                            /**
                            * ********************************************************************
                            */
                            // const token = document.querySelector('meta[name="csrf-token"]').content;
        
                            
                            // let url = 'http://localhost:8000/en/lang';
            
                            // const formData = new FormData();
                            // formData.append('locale', country_lang.value);

                            // // console.log("eeeeeeee", formData)

                            // // return
            
                            // fetch(url, {
            
                            //     headers: {
                            //         // 'Content-Type': 'application/json',
                            //         // 'X-CSRF-TOKEN': token
                            //     },
            
                            //     method: 'post',
                            //     body: formData,
            
                            // }).then(response => {
            
            
                            //     response.json().then(data => {
            
                            //     // console.log("eeeeeeeedddd", data)

            
                            //     })
            
                            // }).catch(error => {
                            //     console.log(error)
                            // })
            
                      
            

                }
                
                
                
            })
        });
    }

 

    // country_lang_div.addEventListener('click', function() {
    //     console.log("country_lang", country_lang.value)
    // })
    // console.log("country_lang", country_lang.value)
   
    // console.log("country_lang", country_lang_div)
    // console.log("country_lang", country_lang.dataset.dataDynamicSelect)
    // country_lang
})