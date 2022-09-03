import React, { Fragment,Component} from 'react';
import { DownloadIcon, RefreshIcon } from '@heroicons/react/outline';
import axios from 'axios';
import { bind } from 'lodash';
import { ajaxurl,youtube } from '../components/Backend';

class Downloader extends Component {

    constructor(props) {
        super(props)

        this.state = {
            refresh: false,
            featch: "",
            data: {
                username: "",
                create_time: "",
                download: "",
                thumb: "",
            },
            show: false,
        }

        this.handleFeatchData = this.handleFeatchData.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }


    handleChange =(name,event) => {
        //more logic here as per the requirement
        this.setState({
            [name]: event.target.value,
        });
    };

    youTubeIdFromLink = (url) => url.match(/(?:https?:\/\/)?(?:www\.|m\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\/?\?v=|\/embed\/|\/)([^\s&\?\/\#]+)/)[1];

    handleFeatchData = () => {
        const self = this;
        self.setState({refresh : true});
        if(self.state.featch.includes("tiktok.com")){
            axios.post(ajaxurl, {
                url: self.state?.featch,
            },{
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then(function (res) {
              const response = res.data;
              console.log(response);
              if(response.success){
                self.setState({
                    refresh : false,
                    show : true,
                    data : response.data,
                })
              }else{
                self.setState({
                    refresh : false,
                    show : false,
                })
              }

            })
            .catch(function (err) {
                self.setState({
                    refresh : false,
                    show : false,
                })
            });
        }
    }


    render() {
      return (
        <Fragment>
          <div className="awesomecoder relative p-4 font-poppins">
              <div className="full flex relative my-1  h-11 justify-between">
                  <input
                   onChange={event => this.handleChange( "featch", event)}
                   value={this.state?.featch}
                   placeholder="Insert video link" type="text"
                   className={` ${ this.state.show ? "border border-red-500 focus:border-red-300/0 focus:ring focus:ring-red-200/0 focus:ring-opacity-50" : "border-gray-300/10 focus:border-primary-300/0 focus:ring focus:ring-primary-200/0 focus:ring-opacity-50" } awesomecoder_app_url block w-screen p-3  shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 `} />
                  <span onClick={this.handleFeatchData} className="bg-primary-400 flex justify-around items-center w-1/5 cursor-pointer rounded-r-md">
                      <span className="md:block hidden text-white font-semibold text-sm pointer-events-none ">
                        {this.state.refresh ? "Loading.." : "Download"}
                      </span>
                      {(() => {
                        if( this.state?.refresh ){
                            return(
                                <RefreshIcon className={ "animate-spin pointer-events-none h-6 w-6 text-white font-semibold text-sm" } />
                            )
                        }else{
                            return(
                                <DownloadIcon className={ "mr-2 pointer-events-none h-6 w-6 text-white font-semibold text-sm" } />
                            )
                        }
                    })()}

                  </span>
              </div>


            {(() => {
                if(this.state.show){
                    return(
                        <div className={` relative w-full my-4`}>
                            <div className="relative flex flex-col sm:flex-row justify-between items-center">
                                {(() => {
                                    if (this.state.data.thumb != ''){
                                        return (
                                            <div className="h-[25rem] md:w-[25rem] w-full max-w-sm bg-gray-200 rounded-md animate-pulse rounded-md" style={{
                                               backgroundImage: `url(${this.state.data.thumb})`,
                                               backgroundRepeat: "no-repeat",
                                               backgroundPosition: "center center",
                                               backgroundSize: "cover"
                                            }}></div>
                                        )
                                    }else{
                                        return (
                                            <div className="h-[25rem] md:w-[25rem] w-full max-w-sm  bg-gray-200 rounded-md animate-pulse" ></div>
                                        )
                                    }
                                })()}

                                <div className="relative sm:w-full md:flex-auto h-[25rem] bg-white shadow-md m-4 mr-0 p-4 rounded-md">
                                    <h3 className="text-slate-600 font-semibold text-xl font-poppins">
                                        This video created by <b>{this.state.data.username}</b>
                                    </h3>
                                    <h3 className="text-slate-600 font-semibold text-xl font-poppins">
                                        Updated at <b>{this.state.data.create_time}</b>
                                    </h3>
                                    <a href={this.state.data.download} target="_blank" className=" bg-primary-400 flex items-center max-w-sm cursor-pointer rounded-md p-2">
                                        <DownloadIcon className={ "mr-2 pointer-events-none h-6 w-6 text-white font-semibold text-sm" } />
                                        <span className="text-white font-semibold text-[1rem] font-poppins pointer-events-none outline-none no-underline ">
                                           Download Now
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    )
                }
            })()}

          </div>

        </Fragment>
      )
    }
}

export default Downloader;