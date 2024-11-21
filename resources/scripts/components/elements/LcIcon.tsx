import React from 'react';
import { LucideProps } from 'lucide-react';

interface LcIconProps extends Omit<LucideProps, 'size'> {
  icon: React.ComponentType<LucideProps>;
  size?: string | number;
}

const LcIcon: React.FC<LcIconProps> = ({ icon: IconComponent, size = 16, style, ...props }) => {
  const customStyle = {
    width: typeof size === 'number' ? `${size}px` : size,
    height: typeof size === 'number' ? `${size}px` : size,
    ...style,
  };

  return <IconComponent {...props} style={customStyle} />;
};

export default LcIcon;
