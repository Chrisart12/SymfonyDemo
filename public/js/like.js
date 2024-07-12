document.addEventListener("DOMContentLoaded", () => {


    function onLikeRecipe(e) {
        e.preventDefault()

        const url = this.href;

        // this est l'élément sur lequel a eu lieu le click (balise a)
        // nextElementSibling est l'élément suivant (div) container-like
        const containerLike = this.nextElementSibling

        // On recherche la class contenue dans l'élément this
        const icon = this.querySelector('.icon-like')
        // On recherche l'info sur l'attribut de l'icon
        let fillColor = icon.getAttribute('fill')

        // On appelle axios
        axios.get(url).then(response => {
            // console.log('response', response)
            let totalLikes = response.data.totalLikes 
            
            containerLike.textContent = totalLikes

            // Gestion de a couleur de l'icon
            if (fillColor === '#000000') {
                icon.setAttribute('fill', '#4caf50')
            } else if(fillColor === '#4caf50') {
                icon.setAttribute('fill', '#000000')
            }
        }).catch(error => {
            console.log('erreur de réponse')
        })
    }

    document.querySelectorAll('.like-btn').forEach(function(link) {
        // console.log("eieiei", link)
        link.addEventListener('click', onLikeRecipe)
    })
})
