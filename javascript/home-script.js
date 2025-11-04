document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.scroll-container');
    
    //duplicate imgs ac ouple times
    container.innerHTML += container.innerHTML + container.innerHTML;
    
    const singleSetWidth = container.scrollWidth / 3;
    
    container.scrollLeft = singleSetWidth;
    
    container.addEventListener('scroll', function() {
        const scroll = container.scrollLeft;
        
        //go back after reaching right edge t5
        if (scroll >= singleSetWidth * 2) {
            container.scrollLeft = scroll - singleSetWidth;
        }
        //go forward after reaching left edge t1
        else if (scroll <= 0) {
            container.scrollLeft = scroll + singleSetWidth;
        }
    });
});