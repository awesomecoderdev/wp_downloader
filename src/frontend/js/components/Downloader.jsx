import React, { Fragment,Component} from 'react';
import { ArrowDownIcon, ArrowSmDownIcon, ArrowSmUpIcon, ChevronDoubleUpIcon, DownloadIcon, RefreshIcon } from '@heroicons/react/outline';
import axios from 'axios';
import { bind } from 'lodash';
import { ajaxurl,youtube } from '../components/Backend';
class Downloader extends Component {

    constructor(props) {
        super(props)

        this.state = {
            refresh: false,
            featch: "",
            show: false,
            type: "mp4",
        }
        this.handleFeatchData = this.handleFeatchData.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }


    handleFileType = () => {
        if(this.state.type == "mp4"){
            this.setState({
                type : "mp3",
            })
        }else{
            this.setState({
                type : "mp4",
            })
        }
    }

    handleChange =(name,event) => {
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
                type: self.state.type
            },{
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then(function (res) {
              const response = res.data;
              if(response.success){
                // console.log(response.data.download);
                self.setState({
                    refresh : false,
                    show : false,
                })
                if(!window.open(response.data.download, "_blank")){
                    self.handleFeatchData();
                }
              }else{
                self.setState({
                    refresh : false,
                    show : show,
                })
              }

            })
            .catch(function (err) {
                self.setState({
                    refresh : false,
                    show : true,
                })
            });
        }
    }

    render() {
      return (
        <Fragment>
          <div className="awesomecoder relative p-4  my-5 font-poppins">
              <div className="full flex relative my-1  h-11 justify-between">
                  <input
                   onChange={event => this.handleChange( "featch", event)}
                   value={this.state?.featch}
                   placeholder="Insert video link" type="text"
                   className={` ${ this.state.show ? "border border-red-500 focus:border-red-300/0 focus:ring focus:ring-red-200/0 focus:ring-opacity-50" : "border-gray-300/10 focus:border-primary-300/0 focus:ring focus:ring-primary-200/0 focus:ring-opacity-50" } awesomecoder_app_url block w-screen p-3  shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 `} />
                    <span onClick={this.handleFileType} className="cursor-pointer bg-primary-400 w-18 border-r flex justify-center items-center text-white font-semibold text-sm ">
                        <span className="w-14 text-center">
                            {this.state.type}
                        </span>
                        <div className="flex justify-center items-center pointer-events-none animate-bounce pr-2 ">
                            <svg xmlns="http://www.w3.org/2000/svg" className={ "pointer-events-none h-5 text-white mt-2 font-semibold text-sm" }  viewBox="0 0 20 20" fill="currentColor">
                              <path fillRule="evenodd" d="M2.24 6.8a.75.75 0 001.06-.04l1.95-2.1v8.59a.75.75 0 001.5 0V4.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0L2.2 5.74a.75.75 0 00.04 1.06zm8 6.4a.75.75 0 00-.04 1.06l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75a.75.75 0 00-1.5 0v8.59l-1.95-2.1a.75.75 0 00-1.06-.04z" clipRule="evenodd" />
                            </svg>
                        </div>
                    </span>
                  <span onClick={this.handleFeatchData} className="bg-primary-400 flex justify-around items-center w-1/5 cursor-pointer rounded-r-md">
                      <span className="md:block hidden text-white font-semibold text-sm pointer-events-none ">
                        {this.state.refresh ? "Processing.." : "Download"}
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
                if( this.state?.show ){
                    return(
                        <p className="my-2 text-sm text-red-500 font-poppins font-semibold">Something went wrong, Please try again or use another link.</p>
                    )
                }
            })()}
          </div>

        </Fragment>
      )
    }
}

export default Downloader;