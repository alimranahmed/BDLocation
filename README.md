# BDLocation
A PHP interface to access Locations of Bangladesh. The data in this project used and slightly modified from [Bangladesh-geolocation](https://github.com/nuhil/bangladesh-geocode)

## Features
1. Can access name, bengali_name etc of divisions, districts, sub districts and unions of Bangladesh  
2. Zero configurations


## Requirements
PHP >= 7

## Installation 
Execute the following command in the terminal while you are in the root directory of your PHP project:

`composer require alimranahmed/bdlocation`

That's it! you are ready to go!

## Usages

Don't forget to use the BD class:
```
use BDLocation\BD;
```

To get all **divisions** of Bangladesh as an array of Location object:
```
BD::division()->all();
```

To get all **districts** of Bangladesh as an array of Location object:
```
BD::district()->all();
```

To get all **sub-districts/upazilas** of Bangladesh as an array of Location object:
```
BD::subDistrict()->all();
```

To get all **unions** of Bangladesh of Bangladesh as an array of Location object:
```
BD::union()->all();
```

To get all the districts of division that start with 3 character `chi`(Chittagong), we can use the following code. Same thing will work for sub-disctrict and union. But in case of sub-district and union we need to pass `district` and `sub_district` respectively.  
```
BD::district()->getWhere('division', 'chi');
```

To get the district that has name `Brahmanbaria` we can use the following code. Same thing will work for division, sub-district and union also. Insead of `name` we can pass `short_name`(first 3 letter of the location), `bengali_name` also. 
```
BD::district()->getWhere('name', 'brahmanbaria');
or 
BD::district()->getWhere('name', '=', 'brahmanbaria');
```

To get all the districts that contains the letter `rahman` in it's name we can use the following code. Same thing will work for division, sub-district and union also. Insead of `name` we can pass `short_name`(first 3 letter of the location), `bengali_name` also.
```
BD::district()->getWhere('name', 'like', 'rahman');
```
**Note:** We have only `=` and `like` as operator here. No other operator will work here. 

## Wishlist
- [ ] Supporting more operators like `like%` `%like` etc in `getWhere()` function 

### Contribution 
**Anyone is always welcome to contribute on the project. If you want to work with:**
1. Just create and issue(even if you want to fix the issue). 
2. After fixing any issue or adding any new feature just send a pull request
3. I will be happy to add your code for the betterment of this project. 
Thanks.

## Licence 
[MIT](https://opensource.org/licenses/MIT)
