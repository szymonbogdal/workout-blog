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
  try{
    const response = await fetch(url, options);
    if(response.status === 204){
      return {status: "success"};
    }

    const result = await response.json();
    return{
      status: response.ok ? "success" : "error",
      ...result
    }
  }catch(e){
    return {status: "error", message: "Unexpected error"}
  } 
}

export default apiCall