// Navbar mobile
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.sidenav');
      var instances = M.Sidenav.init(elems, {
        // specify options here
      });
      // Função para fechar o sidenav
          var elem = document.querySelector(".sidenav");
          var instance = M.Sidenav.getInstance(elem);
          const fechar = document.getElementById("fechar");
          if(fechar){
          fechar.addEventListener('click', function(){
            instance.close();
          })
        }
    }); 

// Modais
const popovers = document.querySelectorAll(".modal");
popovers.forEach(popover => {
  popover.addEventListener("toggle", (event) => {
    if (event.newState === "open") {
      console.log("Popover ",popover.id," foi aberto");
      const body = document.querySelector('body');
      body.style.overflow = 'hidden'; // Desabilita o scroll do body quando o modal está aberto
      console.log('Scroll do body desabilitado');
      // Primeiro inicializa as tabs
      const tabs = document.querySelectorAll('.tabs');
      tabs.forEach(tab => {
        // Verifica se já existe uma instância antes de criar uma nova
        let instance = M.Tabs.getInstance(tab);
        if (!instance) {
          // Verifica se a tab tem links válidos antes de inicializar
          const tabLinks = tab.querySelectorAll('a[href^="#"]');
          let hasValidContent = true;
          
          // Verifica se todos os links das tabs têm conteúdo correspondente
          tabLinks.forEach(link => {
            const contentId = link.getAttribute('href');
            // Valida se o contentId é um seletor válido (não pode ser apenas '#' ou vazio)
            if (contentId && contentId.length > 1 && contentId.startsWith('#')) {
              const content = document.querySelector(contentId);
              if (!content || !content.parentElement) {
                hasValidContent = false;
              }
            } else {
              // Se o href não é válido, marca como inválido
              hasValidContent = false;
            }
          });
          
          if (hasValidContent && tabLinks.length > 0) {
            try {
              instance = M.Tabs.init(tab, {
                swipeable: false, // Desabilitar swipeable para evitar conflitos com carousel
              });
            } catch(error) {
              console.warn('Erro ao inicializar tab:', error);
            }
          } else {
            console.warn('Tab não possui conteúdo válido para inicialização:', tab);
          }
        }
      });

      // Depois inicializa os carousels com um pequeno delay para garantir que as tabs estejam prontas
      setTimeout(() => {
        // Carousel de ícones específico (sem fullWidth para carousel normal)
        const carouselIcons = document.querySelector('#carousel-icones');
        if (carouselIcons && !M.Carousel.getInstance(carouselIcons)) {
          M.Carousel.init(carouselIcons, {
            fullWidth: false,
            indicators: true,
            duration: 200,
            dist: 0,
          });
        }
        
        // Carousel de confortos
        const carouselConfortos = document.querySelector('#carousel-confortos');
        if (carouselConfortos && !M.Carousel.getInstance(carouselConfortos)) {
          M.Carousel.init(carouselConfortos, {
            fullWidth: false,
            indicators: true,
            duration: 200,
          });
        }

        // Outros carousels gerais (como sliders de imagem)
        const otherCarousels = document.querySelectorAll('.carousel:not(#carousel-icones):not(#carousel-confortos)');
        otherCarousels.forEach(carousel => {
          if (!M.Carousel.getInstance(carousel)) {
            M.Carousel.init(carousel, {
              fullWidth: true,
            });
          }
        });
      }, 100);

    } else {
      console.log("Popover ",popover.id," foi fechado");
      const body = document.querySelector('body');
      body.style.overflow = 'auto'; // Habilita o scroll do body quando o modal está fechado
      console.log('Scroll do body habilitado');
      // Inicio da destruição das tabs
      const tabs = document.querySelectorAll('.tabs');
      tabs.forEach(tab => {
        const instance = M.Tabs.getInstance(tab);
        console.log(instance);
        if(instance){
          try {
            // Verifica se o elemento ainda existe no DOM antes de destruir
            if (tab.parentNode) {
              instance.destroy();
              console.log('Tab',tab.id,' destruída');
            }
          } catch(error) {
            console.warn('Erro ao destruir tab:', error);
            // Se falhar, remove a referência manualmente
            tab['M_Tabs'] = undefined;
          }
        }
      });
      // Inicio da destruição dos carousels
      const carousels = document.querySelectorAll('.carousel');
      carousels.forEach(carousel => {
        const instance = M.Carousel.getInstance(carousel);
        if(instance){
          try {
            instance.destroy();
          } catch(error) {
            console.warn('Erro ao destruir carousel:', error);
            // Se falhar, remove a referência manualmente
            carousel['M_Carousel'] = undefined;
          }
        }
      });
    }
  });
});
// Funções para os botões next e prev
  function moveSlides(sliderId, direction) {
    const sliderElem = document.getElementById(sliderId);
    if(sliderElem) {
      const sliderInstance = M.Carousel.getInstance(sliderElem);
      if(direction === 'next') {
        sliderInstance.next();
        console.log('avançando o slide ' + sliderId);
      } else if(direction === 'prev') {
        sliderInstance.prev();
        console.log('retrocedendo o slide ' + sliderId);
      }
    }
  }


// Carousel Institucional home page
  // document.addEventListener('DOMContentLoaded', function() {
  //   const institucional = document.getElementById('institucional-carousel');
  //   const instances = M.Carousel.init(institucional, {
  //      fullWidth: true
  //   });
  //   // Funções para os botões next e prev
  //   function moveSlides(sliderId, direction) {
  //     const sliderElem = document.getElementById(sliderId);
  //     if(sliderElem) {
  //       const sliderInstance = M.Carousel.getInstance(sliderElem);
  //       if(direction === 'next') {
  //         sliderInstance.next();
  //       } else if(direction === 'prev') {
  //         sliderInstance.prev();
  //       }
  //     }
  //   }

  // });
  