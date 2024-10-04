const apiCall = async(url, method = "GET", params = {}) => {
  const options = {
    method,
  }
  if(method === "GET"){
    const queryString = new URLSearchParams(params).toString();
    if(queryString){
      url += "?" + queryString;
    }
  }else if(method === "POST"){
    if(params instanceof FormData){
      options.body = params;
    }else{
      options.body = JSON.stringify(params);
    }
  }
  try {
    const response = await fetch(url, options);
    if(!response.ok){
      throw new Error(`HTTP errror ${response.status}`);
    }
    return await response.json();
  }catch(error){
    console.error(error);
  }
}

export default apiCall