const getPageNumbers = (totalPages = 10, currentPage = 1, showPages = 5) => {
  const pageNumbers = [];
  
  // Show all pages if total pages is small
  if(totalPages <= showPages + 2){
    for(let i = 1; i <= totalPages; i++){
      pageNumbers.push(i);
    }
    return pageNumbers;
  }

  // Always show first page
  pageNumbers.push(1);

  // Current page near start
  if(currentPage <= showPages - 2){
    for(let i = 2; i < showPages; i++){
      pageNumbers.push(i);
    }
    pageNumbers.push(totalPages);
  // Current page near end
  }else if(currentPage >= totalPages - (showPages - 3)){
    for(let i = totalPages - (showPages - 2); i <= totalPages; i++){
      pageNumbers.push(i);
    }
  // Current page in the middle
  }else{
    for(let i = currentPage - 1; i <= currentPage + 1; i++){
      pageNumbers.push(i);
    }
    pageNumbers.push(totalPages);
  }

  return pageNumbers;
};

export default getPageNumbers;